<?php

namespace XML\Transport;

abstract class Application
{
    protected $envirotment;

    protected $auth;

    protected $requestHandler;

    protected $serviceHandler;

    public function __construct(Auth $auth, string $envirotment = 'testing')
    {
        $this->auth = $auth;
        $this->envirotment = $envirotment;
    }

    public function __call($method, $parameters): Response
    {
        $service = {$this->serviceHandler()}::$method($this->envirotment);

        $request = new {$this->requestHandler()}($service, $this->auth);

        return $request->send(...$parameters);
    }

    abstract protected function requestHandler(): string;

    abstract protected function serviceHandler(): string;
}
