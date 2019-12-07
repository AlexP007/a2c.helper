<?php

namespace A2c\Helper;

class HtmlHelper extends HtmlBasic
{
    public static function submit(array $params)
    {
        $params['ATTR']['TYPE'] = 'submit';

        return self::input($params);
    }

    public static function hidden(array $params)
    {
        $params['ATTR']['TYPE'] = 'hidden';

        return self::input($params);
    }

    public static function beginForm(array $params)
    {
        $attr = $params['ATTR'] ?? [];

        return "<form" . self::renderAttributes($attr);
    }

    public static function endForm()
    {
        return '</form>';
    }
}