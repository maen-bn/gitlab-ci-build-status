<?php

namespace Maenbn\Tests\GitlabCiBuildStatus;

use Maenbn\GitlabCiBuildStatus\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testStatusReturnsString()
    {
        $client = new Client('https://gitlab.com/ci', 1, '0e6528a230ce89d8a2939080867a22');
        $status = $client->getStatus();

        $this->assertTrue(is_string($status));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp #Error:.*#
     *
     */
    public function testCurlExceptionThrown(){

        $client = new Client('https://somerandomciwhichiswrong.com/ci', 1, 'somerandomwrongtoken');
        $client->getStatus();

    }
}
