<?php


namespace A2c\Helper;


use CIBlock;


class CIBlockHelper extends CIBlockHelperBasic
{
    protected static $select = null;

    protected static function getData(array $params = array())
    {
        {
            // Определим параметры
            $order = $params['ORDER'] ?? ['SORT' => 'ASC'];
            $filter = $params['FILTER'] ?? [];
            $bIncCnt = $params['B_INC_CNT'] ?? false;

            self::$select = $params['SELECT'] ?? [];

            // вернём объект запроса
            return CIBlock::GetList(
                $order,
                $filter,
                $bIncCnt
            );
        }
    }

    protected static function prepareData($dbResult)
    {
        $result = array();

        if (!self::$select) {
            $result = parent::prepareData($dbResult);
        } else {
            // Получим данные
            while ($item = $dbResult->GetNext()) {
                foreach ($item as $key => $val) {
                    if (!in_array($key, self::$select)) {
                        unset($item[$key]);
                    }
                }
                $result[] = $item;
            }
        }

        return $result;
    }
}