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
        $this->atsms = new Gateway($_ENV['USERNAME'], $_ENV['API_KEY'], true);
    }
}
