<?php

namespace AfricasTalking\Api;

class Airtime extends AbstractApi
{
    /**
     * Send airtime to one or multiple people.
     *
     * @param array|string $recipients array or json formated string
     *
     * @return  [description]
     */
    public function sendAirtime($recipients, Closure $callback = null)
    {

        $response = $this->client->post(sprintf('%s/airtime/send', $this->apiEndpoint), $this->options);
    }

    /**
     * Format airtime recipients
     *
     * @param array|string $recipients array or json formated string
     **/
    protected function formatRecipients($recipients)
    {
        $payload = is_array($recipients) ? json_encode($recipients) : $recipients;
        $this->options(['recipients' => $payload]);
    }
}
