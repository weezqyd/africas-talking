<?php

namespace AfricasTalking\Api;

class Subscriptions extends AbstractApi
{
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
}
