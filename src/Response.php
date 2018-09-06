<?php

namespace Ubl\Soap;

use ArrayObject;

abstract class Response extends ArrayObject
{
    public function __construct(array $attributes)
    {
        parent::__construct($attributes, ArrayObject::STD_PROP_LIST | ArrayObject::ARRAY_AS_PROPS);
    }

    public function __toString()
    {
        return json_encode((array) $this, JSON_PRETTY_PRINT);
    }

    public static function fromXML($xml)
    {
        $response = simplexml_load_string($xml);

        return new static(static::response($response));
    }

    abstract protected static function response($xml);
}
