<?php

namespace A2c\Helper;

use a2c\helper\traits\Loader;
use Bitrix\Main\Data\Cache;

/**
 * Это бызовый класс хелперов
 * работающих с модулем инфоблоков.
 * Осуществляет выборку данных из
 * инфоблоков + кэширование
 *
 * Class CIBlockHelperBasic
 * @package A2c\Helper
 */
abstract class CIBlockHelperBasic
{
    // Подключим трейты
    use Loader;

    /**
     * Тут будет хранится кэш
     */
    const CACHE_DIR = '/a2c_helper_cache';

    /**
     * Дефолтное время кэширования 1 час
     */
    const DEFAULT_TIME = 3600;

    /**
     * C камим модулем работаем
     */
    const MODULE_NAME = 'iblock';

    /**
     * Вернет массив с данными из инфоблока
     * При указании $params['CACHE']['ID'],
     * закеэширует резултат
     *
     * @param array $params
     *
     * @return array|bool
     */
    public static function fetch(array $params)
    {
        // Подключим нужный модуль
        self::includeModule();
        // Если передан айди кэша - закешируем или вернем закешированное
        if ($params['CACHE'] && $params['CACHE']['ID']) {

            $cacheParams = $params['CACHE'];
            $cacheId =  $cacheParams['ID'];
            $cacheTime = $cacheParams['TIME'] ?: self::DEFAULT_TIME;

            $cacheManager = Cache::createInstance();

            if ($cacheManager->initCache($cacheTime, $cacheId, self::CACHE_DIR))
                return $cacheManager->getVars();

            if ($cacheManager->startDataCache() ) {

                $result = self::getData($params);

                $cacheManager->endDataCache($result);
                // Вернем результат
                return $result;
            }
            return false;
        }
        // Иначagentmacbook
        //aе просто вернем результат
        return self::getData($params);
    }

    /**
     * Функция отвечающая за соединение
     * и выборку из инфоблоков
     *
     * @param array $arParams
     * @param bool $cacheManager
     *
     * @return array
     */
    private static function getData(array $arParams = array(), $cacheManager = false)
    {
        // Определим параметры
        $order = $arParams['ORDER'] ?: array('SORT' => 'ASC');
        $filter = $arParams['FILTER'] ?: array();
        $groupBy = $arParams['GROUP_BY'] ?: false;
        $navParams = $arParams['NAV_PARAMS'] ?: array();
        $select = $arParams['SELECT'] ?: array();

        $result = array();

        // Получим имя класса с которым рбаотать
        $class = str_replace('Helper', '',static::class);
        // Получим объект
        $dbResult = $class::GetList(
            $order,
            $filter,
            $groupBy,
            $navParams,
            $select
        );

        // Если что-то пошло не так
        if (!$dbResult && $cacheManager)
            $cacheManager->abortDataCache();

        // Получим данные
        while ($item = $dbResult->GetNext() )
            $result[] = $item;

        return $result;
    }
}