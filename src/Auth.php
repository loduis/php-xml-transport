<?php

namespace XML\Transport;

class Auth extends \XML\Support\DataAccess
{
    protected $fillable = [
        'username' => 'string',
        'password' => 'string',
        'certificate' => 'string'
    ];

    public function toArray()
    {
        return $this->data;
    }
}
