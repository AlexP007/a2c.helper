<?php


namespace A2c\Helper;


class StringHelper
{
    /**
    * @param $data - array,
    * where keys are placeholder
    * and values ara new string
    *
    * @param $str - string, where
    * replacement need to be done
    *
    * @return string
    */

    public static function replacePlaceholder(array $data, string $str) {
        $from = array_map(function($elt) {
            return preg_replace('/(\S+)/', '#$0#', $elt);
        },array_keys($data));

        $to = array_values($data);

        return str_replace($from, $to, $str);
    }
}