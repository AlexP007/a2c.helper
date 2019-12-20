<?php


namespace A2c\Helper\Traits;


trait Researcher
{
    private static $reflectionClass = null;

    private static function initializeReflectionClass()
    {
        if (!self::$reflectionClass)  {
            self::$reflectionClass = new \ReflectionClass(static::class);
        }
    }

    protected static function getMethods()
    {
        self::initializeReflectionClass();
        return self::$reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
    }
}