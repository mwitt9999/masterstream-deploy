<?php

namespace App\Services;

use phpseclib\Net\SSH2;

class SSHClient
{
    private $client;

    public function __construct(){}

    public function getClient()
    {
        return $this->client;
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    public function connect($ipAddress) {
        $client = new SSH2($ipAddress);

        if($client->login('deployer', 'password')) {
            $client->disableQuietMode();
            $this->setClient($client);
        } else {
            $this->setClient(false);
        }
    }
}