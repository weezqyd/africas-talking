<?php

namespace AfricasTalking;

use Http\Exceptions;
use Http\Adapter\AdapterInterface as Client;

class Gateway
{
    use Support\Str;

    protected $sandbox;
    protected $username;
    protected $client;


    public function __construct(Client $client, $username, bool $sandbox = false)
    {
        $this->username = $username;
        $this->sandbox = $sandbox;
        $this->client = $client;
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
     * @return mixed
     **/
    protected function getClass($class)
    {
        $resource = static::studly($class);
        $class    = 'AfricasTalking\\Api\\' . $resource;
        if (\class_exists($class)) {
            return new $class($this->client, $this->username, $this->sandbox);
        }
        throw new Exceptions\ErrorException("The class $class does not exist");
    }
}
