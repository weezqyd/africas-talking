<?php

namespace AfricasTalking\Api;

use Http\Adapter\AdapterInterface;
use AfricasTalking\DetectsEnvironment;

abstract class AbstractApi
{
    use DetectsEnvironment;

    /**
     * Gateway Options.
     *
     * @var array
     */
    protected $options = [];
    /**
     * Set global from.
     *
     * @var string
     */
    protected static $from;
    /**
     * Http Adapter.
     *
     * @var Adapter interface
     */
    protected $client;

    /**
     * Construct a new object to interact with the API.
     *
     * @param string           $username
     * @param AdapterInterface $client
     * @param array            $options
     */
    public function __construct(AdapterInterface $client, $username, bool $sandbox = false)
    {
        $this->client = $client;
        $this->options(['username' => $username]);
        $this->detect($sandbox);
    }

    /**
     * Get the Http adapter
     *
     * @return  \Http\Adapter\AdapterInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Build request parameters.
     *
     * @param array $options [description]
     */
    protected function options(array $options = [])
    {
        foreach ($options as $key => $option) {
            $this->options[$key] = $option;
        }
    }

    /**
     * Run callbacks
     *
     * @return mixed
     **/
    protected function runCallbacks($payload, $callback)
    {
        if (is_callable($callback) && is_array($payload)) {
            return array_map(function ($item) use ($callback) {
                return $callback($item);
            }, $payload);
        }
        return $payload;
    }
}
