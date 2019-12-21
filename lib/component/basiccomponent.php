<?php


namespace A2c\Helper\Component;


use Bitrix\Main\Localization\Loc;

use A2c\Helper\Traits\Thrower,
    A2c\Helper\Collection;

Loc::loadMessages(__DIR__ . '/dictionary.php');

abstract class BasicComponent
{
    use Thrower;

    const SCRIPT_CALLBACK = 'callback';
    const SCRIPT_MAIN = 'main';

    private $prefix = 'a2c_';

    private $idCollection;
    private $scriptCollection;

    /**
     * BasicComponent constructor
     *
     * При инстанцировании определяем коллекции
     */
    final function __construct()
    {
        $this->idCollection = new Collection($this->prefix);
        $this->scriptCollection = new Collection();
    }

    public abstract function begin(array $params);

    public abstract function end(): string;

    /**
     * Генерирует псевдослучайный айди
     * и сохраняет во внутренней таблице
     */
    protected function setId($string)
    {
        $this->idCollection->$string = substr(md5(time() . rand(0, 10000) ), -12);
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
        $id = $this->idCollection->$string;

        self::ensureParameter(
            $id, Loc::getMessage('A2C_HELPER_COMPONENT_PARAMETER_EXCEPTION', ['#PARAMETER#' => $string])
        );

        return $id;
    }

    private function setScript(string $string, string $script)
    {
        $this->scriptCollection->$string = $script;
    }

    public function setCallback(string $callback)
    {
        $this->setScript(self::SCRIPT_CALLBACK, $callback);
    }

    protected function getCallback()
    {
        return $this->scriptCollection->callback;
    }
}