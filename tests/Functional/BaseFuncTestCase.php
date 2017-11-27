<?php

namespace Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseFuncTestCase extends WebTestCase
{
    use LoadFixturesTrait;

    protected static $clientInstance;

    protected static function initClient(array $options = [], array $server = [])
    {
        self::$clientInstance = static::createClient($options, $server);
    }

    protected static function getContainer()
    {
        return self::$clientInstance->getContainer();
    }
}
