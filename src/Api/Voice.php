<?php

namespace AfricasTalking\Api;

use Closure;
use Http\Exceptions\ErrorException;

class Voice extends AbstractApi
{
    /**
     * Make an outbound call through the Voice API.
     *
     * @param string       $from     Number to use to make call
     * @param string       $to
     * @param Closure|null $callback
     *
     * @return mixed API response
     */
    public function call($from, $to, Closure $callback = null)
    {
        $this->options(compact('from', 'to'));
        $body = http_build_query($this->options);
        $response = $this->client->post(sprintf('%s/call', $this->voiceEndpoint), $body);

        return $this->voiceResponse($response, $callback);
    }

    /**
     * Get queued calls from the API.
     *
     * @param string       $phoneNumbers
     * @param string       $queueName
     * @param Closure|null $callback
     *
     * @return mixed API response
     */
    public function queuedCalls($phoneNumbers, $queueName = null, Closure $callback = null)
    {
        $params = [
              'phoneNumbers' => $phoneNumber,
             ];
        $queueName === null ?: $params['queueName'] = $queueName;
        $this->options($params);
        $response = $this->client->get(sprintf('%s/queueStatus', $this->voiceEndpoint), http_build_query($this->options));

        return $this->voiceResponse($response, $callback);
    }
    /**
     * Send airtime to one or multiple people.
     *
     * @param array|string $recipients array or json formated string
     *
     * @return  [description]
     */
    public function uploadFile($url, Closure $callback = null)
    {
        if (! filter_input(FILTER_VALIDATE_URL, $url)) {
            throw new ErrorException("$url is not a valid url.");
        }
        $this->formatRecipients($recipients);
        $response = $this->client->post(sprintf('%s/airtime/send', $this->apiEndpoint), http_build_query($this->options));
        $response = json_decode($response);
        return $this->runCallbacks($response->responses, $callback);
    }

    /**
     * Handle response for the voice endpoint.
     *
     * @param mixed        $response API response
     * @param Clusure|null $callback
     *
     * @return mixed API response
     **/
    protected function voiceResponse($response, $callback)
    {
        $response = json_decode($response);
        if ($response->errorMessage !== 'NONE') {
            throw new ErrorException($response->errorMessage);
        }

        return $this->runCallbacks($response->entries, $callback);
    }
}
