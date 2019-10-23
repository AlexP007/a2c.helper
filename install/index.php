<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

Class a2c_helper extends CModule
{
    /*
     * Определим всё что нужно битриксу
     */
    public function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . "/version.php");

        $this->MODULE_ID = 'a2c.helper';
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("A2C_HELPER_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("A2C_HELPER_MODULE_DESC");
        $this->PARTNER_NAME = Loc::getMessage("A2C_HELPER_PARTNER_NAME");
        $this->MODULE_SORT = 100;
    }

    /*
     * Инсталятор
     */
    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
    }

    /**
     * Деинсталятор
     */
    public function doUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}