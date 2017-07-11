<?php

namespace AfricasTalking\Api;


class Voice extends AbstractApi
{
   
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

}
