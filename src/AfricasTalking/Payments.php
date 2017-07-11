<?php

namespace Elimuswift\SMS\Africastalking;

use Exception;
use InvalidArgumentException;
use GuzzleHttp\Client;

class SmsSender
{
    protected $apiKey;
    protected $username;
    protected $method = 'GET';
    protected $requestBody;
    protected $requestUrl;
    protected $responseBody;
    protected $responseInfo;
    protected $headers;
    protected $options = [];
    protected $from;

    protected $client;
    protected static $debug = false;

    public static $sandbox = true;

        //  API URL ENDPOINTS
    const SMS_URL = 'https://api.africastalking.com/version1/messaging';
    const VOICE_URL = 'https://voice.africastalking.com';
    const USER_DATA_URL = 'https://api.africastalking.com/version1/user';
    const SUBSCRIPTION_URL = 'https://api.africastalking.com/version1/subscription';
    const AIRTIME_URL = 'https://api.africastalking.com/version1/airtime';

        //	Response Status CODES
    const OK = 200;
    const CREATED = 201;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const BAD_REQUEST = 400;
    const NOT_FOUND = 404;
    const SERVER_ERROR = 500;

    public function __construct(Client $client, $username, $apikey, array $options = [])
    {
        $this->apiKey = $apikey;
        $this->username = $username;
        $this->client = $client;
        $this->init($options);
    }

    private function init($options)
    {
        $this->headers = [
                            'Accept' => 'application/json',
                            'apiKey' => $this->apiKey,
                            'Content-Type' => 'application/x-www-form-urlencoded',
                        ];
        $this->options($options);
    }

    public function sendMessage($to, string $message)
    {
        $params = [
                    'username' => $this->username,
                    'message' => $message,
                    'bulkSMSMode' => 1,
                ];
        $params['to'] = is_array($to) ? implode(',', $to) : $to;
        if (!is_null($this->options)) {
            foreach ($this->options as $key => $value) {
                $params[$key] = $value;
            }
        }

        $this->requestBody = http_build_query($params, '', '&');
        $this->requestUrl = self::SMS_URL;
        $this->method = 'POST';

        return $this->send();
    }

    public function getUserData()
    {
        $this->method = 'GET';
        $this->requestUrl = self::USER_DATA_URL.'?username='.$this->username;

        return $this->send()->UserData;
    }

    public function send()
    {
        try {
            $response = $this->client->request($this->method, $this->requestUrl, [
                          'body' => $this->requestBody,
                          'headers' => $this->headers,
                          ]);
            if ((int) $response->getStatusCode() == self::OK || $response->getStatusCode() == self::CREATED) {
                return json_decode($response->getBody(), false);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    protected function options(array $options = null)
    {
        $allowedKeys = [
                'enqueue',
                'keyword',
                'linkId',
                'retryDurationInHours',
                ];
        foreach ($options as $key => $option) {
            if (in_array($key, $allowedKeys) && strlen($option) > 0) {
                $this->options[$key] = $option;
            } else {
                throw new InvalidArgumentException('Invalid key in options array: '.$key);
            }
        }

        return $this;
    }

    public function fetchMessages($lastReceivedId = '')
    {
        $username = $this->username;
        $this->requestUrl = self::SMS_URL.'?username='.$username.'&lastReceivedId='.intval($lastReceivedId);

        $response = $this->send();

        return $response;
    }

    public function createSubscription($phoneNumber, $shortCode, $keyword)
    {
        if (strlen($phoneNumber) == 0 || strlen($shortCode) == 0 || strlen($keyword) == 0) {
            throw new InvalidArgumentException('Please supply phoneNumber, shortCode and keyword');
        }

        $params = [
                'username' => $this->username,
                'phoneNumber' => $phoneNumber,
                'shortCode' => $shortCode,
                'keyword' => $keyword,
                ];
        $this->method = 'POST';
        $this->requestUrl = self::SUBSCRIPTION_URL.'/create';
        $this->requestBody = http_build_query($params, '', '&');

        return $this->send();
    }

    public function deleteSubscription($phoneNumber, $shortCode, $keyword)
    {
        if (strlen($phoneNumber) == 0 || strlen($shortCode) == 0 || strlen($keyword) == 0) {
            throw new InvalidArgumentException('Please supply phoneNumber, shortCode and keyword');
        }

        $params = [
                'username' => $this->username,
                'phoneNumber' => $phoneNumber,
                'shortCode' => $shortCode,
                'keyword' => $keyword,
                ];

        $this->requestUrl = self::SUBSCRIPTION_URL.'/delete';
        $this->requestBody = http_build_query($params, '', '&');

        $this->method = 'POST';

        return $this->send();
    }

    public function fetchPremiumSubscriptions($shortCode, $keyword, $lastReceivedId = 0)
    {
        $username = $this->username;
        $this->requestUrl = self::SUBSCRIPTION_URL.'?username='.$username.'&shortCode='.$shortCode;
        $this->requestUrl .= '&keyword='.$keyword.'&lastReceivedId='.intval($lastReceivedId);

        return $this->send();
    }

    public function call($from, $to)
    {
        if (strlen($from) == 0 || strlen($to) == 0) {
            throw new InvalidArgumentException('Please supply both from and to parameters');
        }

        $params = [
                'username' => $this->username,
                'from' => $from,
                'to' => $to,
                ];

        $this->requestUrl = self::VOICE_URL.'/call';
        $this->requestBody = http_build_query($params, '', '&');

        $this->method = 'POST';

        return $this->send();
    }

    public function getNumQueuedCalls($phoneNumber, $queueName = null)
    {
        $this->requestUrl = self::VOICE_URL.'/queueStatus';
        $params = [
              'username' => $this->username,
              'phoneNumbers' => $phoneNumber,
             ];
        $queueName === null ?: $params['queueName'] = $queueName;
        $this->requestBody = http_build_query($params, '', '&');

        $this->method = 'POST';

        return $this->send();
    }

    public function sendAirtime(array $recipients)
    {
        $params = [
            'username' => $this->username,
            'recipients' => json_encode($recipients),
           ];
        $this->requestUrl = self::AIRTIME_URL.'/send';
        $this->requestBody = http_build_query($params, '', '&');
        $this->method = 'POST';

        return $this->send();
    }
}
