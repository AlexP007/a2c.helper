<?php


namespace A2c\Helper;


use A2c\Helper\Exception\ArgumentException;
use Bitrix\Main\Localization\Loc;

class ArrayHelper
{
    public static function map(array $array, string $key1, $key2): array
    {
        $result = [];

        foreach ($array as $item) {
            if (!is_array($item) ) {
                new ArgumentException(Loc::getMessage('A2C_HELPER_ARRAY_EXCEPTIONS') );
            }

            // Если второй ключ это массив
            if (array_key_exists($key1, $item) && is_array($key2) ) {
                $result[$item[$key1]] = array();

                if (empty($key2) ) {
                    $result[$item[$key1]] = $item;
                } else {
                    foreach ($key2 as $key) {
                        if (array_key_exists($key, $item)) {
                            array_push($result[$item[$key1]], $item[$key]);
                        }
                    }
                }
            } elseif (array_key_exists($key1, $item) && array_key_exists($key2, $item) ) {
                $result[$item[$key1]] = $item[$key2];
            }
        }

        return $result;
    }
}