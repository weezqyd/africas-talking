<?php

namespace AfricasTalking\Api;

use Closure;

class Sms extends AbstractApi
{
    public function sendMessage($to, string $message, array $options = [], Closure $callback = null)
    {
        $params = [
                    'message' => $message,
                    'bulkSMSMode' => 1,
                ];
        $params['to'] = is_array($to) ? implode(',', $to) : $to;
        if (count($options) > 0) {
            foreach ($options as $key => $option) {
                $params[$key] = $option;
            }
        }
        $this->options(array_merge($options, $params));
        $response = $this->client->post(sprintf('%s/messaging', $this->apiEndpoint), http_build_query($this->options));
        $response = json_decode($response, false);
        $recipients = $response->SMSMessageData->Recipients;

        return $this->runCallbacks($recipients, $callback);
    }

    public function fetchMessages($lastReceivedId = '', Closure $callback = null)
    {
        $this->options(compact('lastReceivedId'));
        $response = $this->client->get(sprintf('%s/messages?%s', $this->apiEndpoint, http_build_query($this->options)));
        $response = json_decode($response, false);
        $messages = $response->SMSMessageData->Messages;
        
        return $this->runCallbacks($recipient, $callback);
    }
}
