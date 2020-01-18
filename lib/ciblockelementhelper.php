<?php


namespace A2c\Helper;


/**
 * Class CiBlockHelper
 *
 * Это хелпер упрощает для
 * работы с классом CIBlockElement
 *
 * @package a2c.helper
 * @author  Alexander Panteleev
 */
class CIBlockElementHelper extends CIBlockHelperBasic
{
    protected static function getData(array $params = array())
    {
        {
            // Определим параметры
            $order = $params['ORDER'] ?: array('SORT' => 'ASC');
            $filter = $params['FILTER'] ?: array();
            $groupBy = $params['GROUP_BY'] ?: false;
            $navParams = $params['NAV_PARAMS'] ?: array();
            $select = $params['SELECT'] ?: array();

            // вернём объект запроса
            return \CIBlockElement::GetList(
                $order,
                $filter,
                $groupBy,
                $navParams,
                $select
            );
        }
    }

    public static function getProperties(string $iblockId, string $eltId, array $codes): array
    {
        $propertyCodes = [];
        //Подготовим запрос
        foreach ($codes as $code) {
            $propertyCodes[$code] = 'PROPERTY_' . $code;
        }

        $resultCodes = self::fetchOne([
            'FILTER' => ['=IBLOCK_ID' => $iblockId, '=ID' => $eltId],
            'SELECT' => array_values($propertyCodes),
        ]);

        $result = [];

        // Соберем нормальный ассоциативный массив code => value
        foreach ($propertyCodes as $code => $key) {
            if(!empty($value = $resultCodes[$key . '_VALUE']) ) {
                $result[$code] = $value;
            }
        }

        return $result;
    }

    public static function subQuery($field, $filter)
    {
        // Подключим нужный модуль
        self::includeModule();

        return \CIBlockElement::SubQuery($field, $filter);
    }
}