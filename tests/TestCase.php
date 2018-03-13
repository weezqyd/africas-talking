<?php

namespace AfricasTalking\Tests;

use AfricasTalking\Gateway;
use PHPUnit\Framework\TestCase as PHPUnit;

abstract class TestCase extends PHPUnit
{
    protected $atsms;

    public function setUp()
    {
        Gateway::$options = ['verify' => false];
        $this->atsms = new Gateway(getenv('AT_USERNAME'), getenv('API_KEY'), true);
    }
}
