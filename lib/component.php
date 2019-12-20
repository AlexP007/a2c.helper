<?php


namespace A2c\Helper;


use A2c\Helper\Component\Collapse;

class Component
{
    public static function collapse(): Collapse
    {
        return new Collapse();
    }
}