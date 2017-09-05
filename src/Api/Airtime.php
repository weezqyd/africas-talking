<?php

namespace AfricasTalking\Api;

use Closure;

class Airtime extends AbstractApi
{
    /**
     * Send airtime to one or multiple people.
     *
     * @param array|string $recipients array or json formated string
     *
     * @return  [description]
     */
    public function send($recipients, Closure $callback = null)
    {
        $this->formatRecipients($recipients);
        $response = $this->client->post(sprintf('%s/airtime/send', $this->apiEndpoint), http_build_query($this->options));
        $response = json_decode($response);
        return $this->runCallbacks($response->responses, $callback);
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
