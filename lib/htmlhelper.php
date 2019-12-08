<?php

namespace A2c\Helper;


use A2c\Helper\Exception\ArgumentException;
use Bitrix\Main\Localization\Loc;

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

    public static function select(array $params): string
    {
        $params['ATTR']['TYPE'] = 'select';

        if (isset($params['INNER_HTML']) ) {
            throw new ArgumentException(Loc::getMessage('A2C_HELPER_ARGUMENT_EXCEPTIONS'), ['#PARAM#' => 'INNER_HTML']);
        }

        if (!empty($params['OPTIONS']) ) {
            if (!is_array($params['OPTIONS']) ) {
                throw new ArgumentException(Loc::getMessage('A2C_HELPER_ARRAY_TYPE_EXCEPTION', ['#PARAM#' => 'OPTIONS']));
            }
            $params['INNER_HTML'] = '';

            if (isset($params['DEFAULT']) ) {
                $params['INNER_HTML'] .= self::option([
                    'INNER_HTML' => $params['DEFAULT'],
                    'ATTR' => [
                        'SELECTED' => null,
                        'DISABLED' => null,
                        'HIDDEN' => null,
                    ]
                ]);
            }

            $lastKey = key(array_slice($params['OPTIONS'], -1, 1, true));

            foreach ($params['OPTIONS'] as $key => $val) {
                $params['INNER_HTML'] .= self::option(['INNER_HTML' => $val, 'ATTR' => ['VALUE' => $key]] );
                if ($lastKey != $key) {
                    $params['INNER_HTML'] .= "\n";
                }
            }
        }
        [$innerHtml, $attr] = self::getParams($params);

        return self::renderElement('select', $innerHtml, $attr);
    }

    private static function option(array $params): string
    {
        [$innerHtml, $attr] = self::getParams($params);

        return self::renderElement('option', $innerHtml, $attr);
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

        if (!isset($attr['TYPE']) ) {
            $attr['TYPE'] = 'text';
        }

        $result = self::renderElement('input', $innerHtml, $attr);

        if (!empty($label) ) {
            if (isset($label['POSITION'])
                && $label['POSITION'] === 'BEFORE') {
                $result = self::label($label) . "\n" . $result;
            } else {
                $result .= "\n" . self::label($label);
            }
        }

        return $result;
    }
}