<?php

namespace AfricasTalking;

use Http\Exceptions;
use Http\Adapter\GuzzleHttpAdapter;
use Http\Adapter\AdapterInterface as Client;

class Gateway
{
    use Support\Str;
    /**
     * Sandbox flag.
     *
     * @var bool
     */
    protected $sandbox;
    /**
     * Authenticarion Username.
     *
     * @var string
     */
    protected $username;
    /**
     * Client Adapter.
     *
     * @var \Http\Adapter\AdapterInterface
     */
    protected $client;
    /**
     * Additional  adapter options.
     *
     * @var array
     */
    public static $options = [];

    /**
     * Instantiate the gateway and inject the HTTP Adapter.
     *
     * @param string      $username [description]
     * @param string      $token    [description]
     * @param bool|bool   $sandbox  [description]
     * @param Client|null $adapter  [description]
     */
    public function __construct($username, $token, $sandbox = false, Client $adapter = null)
    {
        $this->username = $username;
        $this->sandbox = $sandbox;
        $this->createAdapter($adapter, $token);
    }

    /**
     * Call API resources as though the were Vultr::class properties.
     *
     * @param mixed $resource
     *
     * @return mixed Api Resource if it exists
     **/
    public function __get($resource)
    {
        return $this->getClass($resource);
    }

    /**
     * Create resource instance.
     *
     * @param string $class Class to instanciate
     *
     * @return mixed
     **/
    protected function getClass($class)
    {
        $resource = static::studly($class);
        $class = 'AfricasTalking\\Api\\'.$resource;
        if (\class_exists($class)) {
            return new $class($this->client, $this->username, $this->sandbox);
        }
        throw new Exceptions\ErrorException("The class $class does not exist");
    }

    /**
     * Set the default client adapter.
     *
     * @param \Http\Adapter\AdapterInterface $adapter
     **/
    public function setClient(Client $adapter)
    {
        $this->client = $adapter;
    }

    /**
     * Set up the Guzzle adapter.
     *
     * @param string $token Api Token
     **/
    protected function createGuzzleAdapter($token)
    {
        $default = ['headers' => [
                        'apikey' => $token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                ];
        $options = array_merge($default, static::$options);
        $adapter = new GuzzleHttpAdapter($options);
        $this->setClient($adapter);
    }

    /**
     * Create a client adapter.
     *
     * @param Http\Adapter\AdapterInterface|null $adapter
     * @param string|null                        $token   API Token
     **/
    protected function createAdapter($adapter, $token = null)
    {
        if ($adapter instanceof Client) {
            $this->setClient($adapter);
        } else {
            $this->createGuzzleAdapter($token);
        }
    }
}
