<?php


namespace A2c\Helper\Traits;


use A2c\Helper\Exception\LogicException,
    A2c\Helper\Exception\ParameterException;

trait Thrower
{
    protected static function ensure(bool $expr, string $message)
    {
        if (!$expr) {
            throw new \Exception($message);
        }
    }

    protected static function ensureLogic(bool $expr, string $message)
    {
        if (!$expr) {
            throw new LogicException($message);
        }
    }

    protected static function ensureParameter(bool $expr, string $message)
    {
        if (!$expr) {
            throw new ParameterException($message);
        }
    }
}