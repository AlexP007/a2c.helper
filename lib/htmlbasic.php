<?php


namespace A2c\Helper;


abstract class HtmlBasic
{
    private static $voidElements = ['input'];

    protected static function getParams(array $params): array
    {
        $attr = $params['ATTR'] ?? [];

        if ($params['ID']) {
            $attr['ID'] = $params['ID'];
        }
        if ($params['FOR']) {
            $attr['FOR'] = $params['FOR'];
        }
        if ($params['CLASS']) {
            $attr['ID'] = $params['CLASS'];
        }

        $label = $params['LABEL'] ?? [];
        $innerHtml = $params['INNER_HTML'] ?? '';

        return [$innerHtml, $attr, $label];
    }

    public static function renderElement(string $name, string $innerHtml, array $attr): string
    {
        return  self::beginTag($name, $attr) . self::endTag($name, $innerHtml);
    }

    private static function beginTag(string $name, array $attr): string
    {
        if ($name === null || $name === false) {
            return '';
        }

        return "<$name" . self::renderAttributes($attr);
    }

    private static function endTag(string $name, string $innerHtml): string
    {
        if (in_array($name, self::$voidElements) ) {
            return '>';
        }

        return "$innerHtml</$name>";
    }

    protected static function renderAttributes(array $attr): string
    {
        if (empty($attr) ) {
            return '';
        }

        $result = ' ';

        foreach ($attr as $key => $val) {
            $attribute = strtolower($key);

            $result .= "$attribute=\"$val\"";
        }

        return $result;
    }
}