<?php


namespace A2c\Helper;


class Collection
{
    private $collection = [];

    private $prefix = '';
    private $postfix = '';

    public final function __construct(string $prefix = null, string $postfix = null)
    {
        $this->prefix = $prefix;
        $this->postfix = $postfix;
    }

    public final function __set($name, $value)
    {
        $this->collection[$name] = $value;
    }

    public function __get($name)
    {
        $value = $this->collection[$name];

        $prefix = $this->prefix;
        $postfix = $this->postfix;

        return $prefix . $value . $postfix;
    }

    public function getCollection()
    {
        $result = [];

        foreach ($this->collection as $key => $val) {
            $result[$key] = $this->$key;
        }

        return $result;
    }
}