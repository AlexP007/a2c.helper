<?php


namespace A2c\Helper\Component;


use Bitrix\Main\Localization\Loc;

use A2c\Helper\Traits\Thrower;

Loc::loadMessages(__DIR__ . '/dictionary.php');

abstract class BasicComponent
{
    use Thrower;

    protected $prefix = 'a2c_';

    private $idContainer = [];

    final function __construct()
    {

    }

    /**
     * Генерирует псевдослучайный айди
     * и сохраняет во внутренней таблице
     */
    protected function generateId($string)
    {
        $this->idContainer[$string] = substr(md5(time() . rand(0, 10000) ), -12);
    }

    /**
     * Достает id из внутреней таблицы
     * предварительно проверив его существование
     *
     * @param string $string
     * @return string
     * @throws \A2c\Helper\Exception\ParameterException
     */
    protected function getId(string $string): string
    {
        self::ensureParameter(
            $this->idContainer[$string],
            Loc::getMessage('A2C_HELPER_COMPONENT_PARAMETER_EXCEPTION', ['#PARAMETER#' => $string])
        );
        return $this->prefix . $this->idContainer[$string];
    }

    abstract function begin(array $params);

    abstract function end(): string;
}