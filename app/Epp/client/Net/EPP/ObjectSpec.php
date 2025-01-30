<?php

class Net_EPP_ObjectSpec
{
    public static $_spec = [
        'domain' => [
            'xmlns' => 'urn:ietf:params:xml:ns:domain-1.0',
            'id' => 'name',
            'schema' => 'urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd',
        ],
        'host' => [
            'xmlns' => 'urn:ietf:params:xml:ns:host-1.0',
            'id' => 'name',
            'schema' => 'urn:ietf:params:xml:ns:host-1.0 host-1.0.xsd',
        ],
        'contact' => [
            'xmlns' => 'urn:ietf:params:xml:ns:contact-1.0',
            'id' => 'id',
            'schema' => 'urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd',
        ],
    ];

    public static function id($object)
    {
        return self::$_spec[$object]['id'];
    }

    public static function xmlns($object)
    {
        return self::$_spec[$object]['xmlns'];
    }

    public static function schema($object)
    {
        return self::$_spec[$object]['schema'];
    }
}
