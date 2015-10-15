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
}
