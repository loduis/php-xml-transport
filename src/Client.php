<?php

namespace XML\Transport;

use SoapVar;
use SoapHeader;
use SoapClient;
use Illuminate\Support\Arr;

class Client extends SoapClient
{
    const WSS_NS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';

    public function __construct(string $wsdl, array $options = [])
    {
        $auth = Arr::pull($options, 'auth');
        parent::__construct($wsdl, $options);
        if ($auth) {
            $this->__setSoapHeaders(
                $this->securityHeader(
                    $auth
                )
            );
        }
    }

    protected function securityHeader($auth)
    {
        $security = $this->soapVar(
            SOAP_ENC_OBJECT,
            null,
            $this->soapVar(
                SOAP_ENC_OBJECT,
                'UsernameToken',
                $this->userToken($auth)
            )
        );

        return new SoapHeader(static::WSS_NS, 'Security', $security, false);
    }

    protected function userToken($auth)
    {
        return [
            $this->soapVar(XSD_STRING, 'Username', $auth->username),
            $this->soapVar(XSD_STRING, 'Password', $auth->password),
        ];
    }

    protected function soapVar($type, $field, $value, $namespace = self::WSS_NS)
    {
        if ($type == SOAP_ENC_OBJECT && !is_array($value)) {
            $value = [$value];
        }

        return new SoapVar(
            $value,
            $type,
            null,
            static::WSS_NS,
            $field,
            $namespace
        );
    }
}
