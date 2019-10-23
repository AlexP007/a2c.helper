<?php

use \Bitrix\Main\Localization\Loc;

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

        $this->MODULE_ID = 'A2C.helper';
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("A2C_HELPER_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("KODIX_AC_MODULE_DESC");
        $this->PARTNER_NAME = Loc::getMessage("KODIX_AC_PARTNER_NAME");
        $this->MODULE_SORT = 100;

    }

    /*
     * Инсталятор
     */
    public function doInstall()
    {

    }

    /**
     * Деинсталятор
     */
    public function doUninstall()
    {

    }
}