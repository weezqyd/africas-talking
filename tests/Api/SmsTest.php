<?php

namespace AfricasTalking\Tests\Api;

use AfricasTalking\Tests\TestCase;

class SmsTest extends TestCase
{
    protected $sms;

    /**
     * Test that a message can be sent.
     *
     * @covers \AfricasTalking\Api\Sms::sendMessage
     **/
    public function testSendMessage()
    {
        $response = $this->sms->sendMessage('+254700123456', 'testSendMessage');
        $this->runCallback($response);
    }

    /**
     * Test that a message can be sent to multiple Recipients.
     *
     * @covers \AfricasTalking\Api\Sms::sendMessage
     **/
    public function testSendMessageToMultipleRecipients()
    {
        $recipients = ['+254700123123', '+254700123987', '+254700123400', '+254700123222'];
        $response = $this->sms->sendMessage($recipients, 'testSendMessage');
        $this->runCallback($response);
    }

    /**
     * Set Sms property for evry test.
     *
     * @covers \AfricassTalking\Apj\AbstractApi::__construct
     * @covers \AfricassTalking\Gateway::__construct
     * @covers \AfricassTalking\Gateway::__get
     * @covers \AfricassTalking\Gateway::getClass
     * @covers \AfricassTalking\Gateway::studly
     **/
    public function setUp()
    {
        parent::setUp();
        $this->sms = $this->atsms->sms;
    }

    /**
     * Test that a message is sent with options.
     *
     * @covers \AfricasTalking\Api\Sms::sendMessage
     **/
    public function testSendMessageWithOptions()
    {
        $response = $this->sms->sendMessage('+254700123456', 'test SendMessage', ['from' => '22123', 'enqueue' => '1']);
        $this->runCallback($response);
    }

    /**
     * Test that a message is sent with options and a callback.
     *
     * @covers \AfricasTalking\Api\Sms::sendMessage
     * @depends testSendMessageWithOptions
     **/
    public function testSendMessageWithOptionsAndCallback()
    {
        $callback = function ($recipient) {
            return $this->assertEquals($recipient->status, 'Success');
        };
        $response = $this->sms->sendMessage('+254700123456', 'test SendMessage', ['from' => '22123', 'enqueue' => '1'], $callback);
    }

    /**
     * Test that it should fail with invalid number.
     *
     * @covers \AfricasTalking\Api\Sms::sendMessage
     * @depends testSendMessageWithOptionsAndCallback
     **/
    public function testSendMessageFailsWithInvalidNumber()
    {
        $callback = function ($recipient) {
            return $this->assertEquals($recipient->status, 'Invalid Phone Number');
        };
        $response = $this->sms->sendMessage('0123456', 'testSendMessage', [], $callback);
    }

    /**
     * Test that it should fail with invalid number.
     *
     * @covers \AfricasTalking\Api\Sms::fetchMessages
     **/
    public function testShouldFetchMessages()
    {
        $callback = function ($message) {
            return array_map(function ($attribute) use ($message) {
                return $this->assertObjectHasAttribute($attribute, $message);
            }, ['to', 'id', 'message', 'date']);
        };
        $this->sms->fetchMessages(0, $callback);
        $this->assertEquals($this->sms->client->getStatusCode(), 200);
    }

    /**
     * Test that it should fail with empty message.
     *
     * @covers \AfricasTalking\Api\Sms::sendMessage
     * @depends testSendMessageWithOptionsAndCallback
     * @expectedException \Http\Exceptions\HttpException
     **/
    public function testSendMessageFailsWithEmptyMessage()
    {
        $this->sms->sendMessage('+254700123456', '');
    }

    /**
     * Assert that messages were send.
     *
     * @return object
     **/
    protected function runCallback($response)
    {
        return array_map(function ($recipient) {
            return $this->assertEquals($recipient->status, 'Success');
        }, $response);
    }
}
