<?php

namespace A2c\Helper;

use CIBlockElement;

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
            return CIBlockElement::GetList(
                $order,
                $filter,
                $groupBy,
                $navParams,
                $select
            );
        }
    }
}