<?php

namespace AfricasTalking\Tests;

use AfricasTalking\Gateway;
use Http\Adapter\GuzzleHttpAdapter;
use PHPUnit\Framework\TestCase as PHPUnit;

abstract class TestCase extends PHPUnit
{
    protected $atsms;

    public function setUp()
    {
        $adapter = new GuzzleHttpAdapter(['headers' => [
                                                            'apiKey' => $_ENV['API_KEY'],
                                                            'Accept' => 'application/json',
                                                            'Content-Type' => 'application/x-www-form-urlencoded'
                                                        ]
                                                    ]);
        $this->atsms = new Gateway($adapter, $_ENV['USERNAME'], true);
    }
}
