<?php

namespace A2c\Helper;

class HtmlHelper extends HtmlBasic
{
    public static function submit(array $params): string
    {
        $params['ATTR']['TYPE'] = 'submit';

        return self::input($params);
    }

    public static function hidden(array $params): string
    {
        $params['ATTR']['TYPE'] = 'hidden';

        return self::input($params);
    }

    public static function checkbox(array $params): string
    {
        $params['ATTR']['TYPE'] = 'checkbox';

        return self::input($params);
    }

    public static function label(array $params): string
    {
        [$innerHtml, $attr] = self::getParams($params);

        return self::renderElement('label', $innerHtml, $attr);
    }

    public static function beginForm(array $params): string
    {
        $attr = $params['ATTR'] ?? [];

        return "<form" . self::renderAttributes($attr) . '>';
    }

    public static function endForm(): string
    {
        return '</form>';
    }

    public static function input(array $params): string
    {
        [$innerHtml, $attr, $label] = self::getParams($params);

        $result = self::renderElement('input', $innerHtml, $attr);

        if (!empty($label) ) {
            $result .= "\n" . self::label($label);
        }

        return $result;
    }
}