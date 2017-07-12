<?php

namespace AfricasTalking\Api;

use Closure;

class Voice extends AbstractApi
{
   
    public function call($from, $to, Closure $callback)
    {
        $this->options(compact('from', 'to'));
        $body = http_build_query($this->options);
        $response = $this->client->post(sprintf('%s/call', $this->voiceEndpoint), $body);

        return $this->runCallbacks($response, $callback);
    }

    public function queuedCalls($phoneNumber, $queueName = null)
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
}
