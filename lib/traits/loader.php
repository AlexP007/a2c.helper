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
    protected static function includeModule()
    {
        try {
            BxLoader::includeModule(static::MODULE_NAME)
            or die("something wrong with" . static::MODULE_NAME);
        } catch(LoaderException $e) {
            die("something wrong with" . static::MODULE_NAME);
        }
    }
}