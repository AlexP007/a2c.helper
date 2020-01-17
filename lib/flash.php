<?php


namespace A2c\Helper;

use Bitrix\Main\Web\Cookie,
    Bitrix\Main\Application;

class Flash
{
    const PREFIX = 'A2C_HELPER_FLASH';

    /**
     * Устанавливает флэш сообщение
     *
     * @param string $name
     * @param string $value
     * @return bool
     * @throws \Bitrix\Main\SystemException
     */
    public static function set(string $name, string $value): bool
    {
        $cookie = new Cookie(self::PREFIX . $name, $value, time() + 1);

        Application::getInstance()->getContext()->getResponse()->addCookie($cookie);

        return true;
    }

    /**
     * Возвращает флэш сообщение
     *
     * @param string $name
     * @return string/null
     * @throws \Bitrix\Main\SystemException
     */
    public static function get(string $name)
    {
        return Application::getInstance()->getContext()->getRequest()->getCookie(self::PREFIX . $name);
    }
}