<?php

namespace A2c\Helper\Traits;

use Bitrix\Main\Loader as BxLoader;
use Bitrix\Main\LoaderException;

/**
 * Trait Loader
 *
 * Этот трейт предоставляет статический
 * метод-обертку над методом
 * Loader::includeModule
 *
 * @author  Alexander Panteleev
 * @package a2c.helper
 */
trait Loader
{
    /**
     * @param string $moduleName
     *
     * @return void
     */
    private static function includeModule(string $moduleName)
    {
        try {
            BxLoader::includeModule($moduleName) or die("something wrong with $moduleName");
        } catch(LoaderException $e) {
            die("something wrong with $moduleName");
        }
    }
}