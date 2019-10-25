<?php

namespace A2c\Helper;

use CIBlockSection;

/**
 * Class CiBlockHelper
 *
 * Это хелпер упрощает для
 * работы с классом CIBlockSection
 *
 * @package a2c.helper
 * @author  Alexander Panteleev
 */
class CIBlockSectionHelper extends CIBlockHelperBasic
{
    protected static function getData(array $params = array())
    {
        {
            // Определим параметры
            $order = $params['ORDER'] ?: array('SORT' => 'ASC');
            $filter = $params['FILTER'] ?: array();
            $bIncCnt = $params['B_INC_CNT'] ?: false;
            $select = $params['SELECT'] ?: array();
            $navParams = $params['NAV_PARAMS'] ?: array();

            // вернём объект запроса
            return CIBlockSection::GetList(
                $order,
                $filter,
                $bIncCnt,
                $select,
                $navParams
            );
        }
    }
}