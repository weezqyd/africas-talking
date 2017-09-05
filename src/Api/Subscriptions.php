<?php

namespace AfricasTalking\Api;

use Closure;
use Http\Exceptions\ErrorException;

class Subscriptions extends AbstractApi
{
    public function createSubscription($phoneNumber, $shortCode, $keyword, $checkoutToken = null)
    {
        $checkoutToken = $this->token($phoneNumber);

        $this->options(compact('checkoutToken', 'phoneNumber', 'shortCode', 'keyword'));
        $response = $this->client->post(sprintf('%s/subscriptions/create', $this->apiEnpoint), http_build_query($this->options));
        return json_decode($response);
    }

    public function deleteSubscription($phoneNumber, $shortCode, $keyword)
    {
        $this->options(compact('phoneNumber', 'shortCode', 'keyword'));
        $body = http_build_query($this->options, '', '&');
        $response = $this->client->post(sprintf('%s/subscriptions/delete', $this->apiEnpoint), $body);
    }

    public function fetchPremiumSubscriptions($shortCode, $keyword, $lastReceivedId = 0, Closure $callback = null)
    {
        $username = $this->username;
        $this->options(compact('lastReceivedId', 'shortCode', 'keyword'));
        $query = http_build_query($this->options);
        $response = $this->client->get(sprintf('%s/subscriptions?%s', $this->apiEnpoint, $query));

        return $this->runCallbacks($response->responses, $callback);
    }

    public function token($phoneNumber)
    {
        $response = $this->client->post('https://api.africastalking.com/checkout/token/create', http_build_query(compact('phoneNumber')));
        $response = json_decode($response);
        if ($response->description === 'Success') {
            return $response->token;
        }
        throw new ErrorException($response->description);
    }
}
