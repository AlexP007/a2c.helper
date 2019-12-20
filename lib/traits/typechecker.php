<?php


namespace A2c\Helper\Traits;

use Bitrix\Main\Localization\Loc;

use A2c\Helper\Exception\ArgumentException;

Loc::loadMessages(__FILE__);

trait TypeChecker
{
    public static function typeArray(array $array, string $key)
    {
        if (!is_array($array[$key]) ){
            throw new ArgumentException(Loc::getMessage('A2C_HELPER_ARRAY_TYPE_EXCEPTION', ['#PARAM#' => $key]) );
        }
    }

    public static function typeArrayAll(array $array)
    {
        $keys = array_keys($array);

        foreach ($keys as $key) {
            self::typeArray($array, $key);
        }
    }
}