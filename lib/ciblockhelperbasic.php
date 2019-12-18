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
     * Функция отвечающая за соединение
     * и выборку из инфоблоков
     *
     * @param array $arParams
     *
     * @return array
     */
    abstract protected static function getData(array $params = array());

    /**
     * Вернет массив с данными из инфоблока
     * При указании $params['CACHE']['ID'],
     * закеэширует резултат
     *
     * @param array $params
     *
     * @return array|bool
     */
    public static function fetch(array $params): array
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

                $dbResult = static::getData($params);

                // Если что-то пошло не так
                if (!$dbResult)
                    $cacheManager->abortDataCache();

                $result = self::prepareData($dbResult);

                $cacheManager->endDataCache($result);
                // Вернем результат
                return $result;
            }
            return false;
        }
        // Иначе просто вернем результат
        $dbResult = static::getData($params);
        if (!$dbResult) {
            return false;
        }
        return static::prepareData($dbResult);
    }

    /**
     * Просто соберет все в массив
     *
     * @param $dbResult
     * @return array
     */
    protected static function prepareData($dbResult)
    {
        $result = array();

        // Получим данные
        while ($item = $dbResult->GetNext() )
            $result[] = $item;

        return $result;
    }

    /**
     * Обертка над fetch
     *
     * @param array $params
     */
    public static function fetchOne(array $params): array
    {
        return self::fetch($params)[0];
    }
}