<?php


namespace A2c\Helper;


abstract class HtmlBasic
{
    private static $voidElements = ['input'];

    protected static function getParams(array $params): array
    {
        $attr = $params['ATTR'] ?? [];
        $innerHtml = $params['INNER_HTML'] ?? '';

        return [$innerHtml, $attr];
    }

    public static function input(array $params): string
    {
        [$innerHtml, $attr] = self::getParams($params);

        return self::renderElement('input', $innerHtml, $attr);
    }

    public static function renderElement(string $name, string $innerHtml, array $attr): string
    {
        return  self::beginTag($name, $attr) . $innerHtml . self::endTag($name);
    }

    private static function beginTag(string $name, array $attr): string
    {
        if ($name === null || $name === false) {
            return '';
        }

        return "<$name" . self::renderAttributes($attr) . '>' ;
    }

    private static function endTag(string $name): string
    {
        if (in_array($name, self::$voidElements) ) {
            return '>';
        }

        return "</$name>";
    }

    protected static function renderAttributes(array $attr): string
    {
        if (empty($attr) ) {
            return '';
        }

        $result = ' ';

        foreach ($attr as $key => $val) {
            $attribute = strtolower($key);
            $value = strtolower($val);

            $result .= "$attribute=\"$value\"";
        }

        return $result;
    }
}