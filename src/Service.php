<?php

namespace XML\Transport;

use ReflectionClass;
use RuntimeException;

abstract class Service
{
    protected $values;

    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
        $reflection = new ReflectionClass(static::class);
        $constants = $reflection->getConstants();
        $services = [];
        foreach ($constants as $key => $value) {
            if (strpos($key, '_WSDL') === false) {
                $services[$value] = $constants[$key . '_WSDL'];
            }
        }
        $this->values = $services;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getWsdl()
    {
        $service = $this->name;

        $this->validate($service);

        return $this->values[$service];
    }

    public static function __callStatic($name, $arguments)
    {
        if (!$arguments) {
            $arguments = ['production'];
        }
        if ($arguments) {
            list($className) = $arguments;
            $className = static::class. '\\' . ucfirst($className);
        }
        $service = constant($className . '::' . strtoupper($name));

        $instance = new $className($service);

        $instance->validate($service);

        return $instance;
    }

    public function validate($service)
    {
        if (!$this->values[$service] ?? false) {
            throw new RuntimeException('Not support service: ' . $service);
        }
    }
}
