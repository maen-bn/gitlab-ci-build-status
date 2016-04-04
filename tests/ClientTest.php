<?php

namespace Maenbn\Tests\GitlabCiBuildStatus;

use Maenbn\GitlabCiBuildStatus\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testStatusReturnsString()
    {
        $client = new Client('https://gitlab.com/api/v3', 1031075, getenv('GITLAB_PRIVATE_KEY'));
        $status = $client->getStatus();
        $this->assertTrue(is_string($status));
    }

    public function testFailedStatusReturnsString()
    {
        $client = new Client('https://gitlab.com/api/v3', 1031075, getenv('GITLAB_PRIVATE_KEY'));
        $status = $client->getStatus('failing');
        $this->assertTrue(is_string($status));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp #Error:.*#
     *
     */
    public function testCurlExceptionThrown(){

        $client = new Client('https://gitlab.com/api/v3', 1, 'somerandomwrongtoken');
        $client->getStatus();

    }
}
