<?php

namespace Ubl\Transport;

abstract class Request
{
    protected $soap;

    protected $auth;

    protected $service;

    public function __construct(Service $service, Auth $auth)
    {
        $this->soap = $this->soapClient($service->getWsdl(), [
            'trace'      => 1,
            'exceptions' => true,
            'auth'       => $auth,
            'version'    => SOAP_1_2
        ]);
        $this->auth = $auth;
        $this->service = $service;
    }

    public function call($action, $arguments)
    {
        if (count($arguments) == 1 && !empty($arguments[0]) && is_array($arguments[0])) {
            $arguments = $arguments[0];
        }

        $response = $this->soap->$action($arguments);

        return $response;
    }

    public function __call($action, $arguments)
    {
        return $this->call($action, $arguments);
    }

    protected abstract function soapClient($wsdl, $options);
}
