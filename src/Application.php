<?php

namespace XML\Transport;

class Application
{
    protected $envirotment;

    protected $auth;

    public function __construct(Auth $auth, string $envirotment = 'testing')
    {
        $this->auth = $auth;
        $this->envirotment = $envirotment;
    }

    public function __call($method, $parameters)
    {
        $service = Service::$method($this->envirotment);

        $request = new Request($service, $this->auth);

        return $request->send(...$parameters);
    }
}
