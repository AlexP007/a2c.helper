<?php


namespace A2c\Helper\Component;


use Bitrix\Main\Localization\Loc;


Loc::loadMessages(__FILE__);

class Collapse extends BasicComponent
{
    private $begin;
    private $dispatcher;
    private $target;
    private $end;

    private $event;

    public function begin(array $array)
    {
        // Этот метод можно вызвать только один раз
        self::ensureLogic(!$this->begin, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_EXCEPTION', ['#METHOD#' => 'before()']) );

        $this->generateId('target');
        $this->generateId('dispatcher');

        $this->event = $array['EVENT'] ?? 'click';

        $this->begin = true;
    }

    public function dispatcher(): string
    {
        // Этот метод может быть вызван только после begin
        self::ensureLogic($this->begin, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_BEFORE_EXCEPTION', ['#METHOD#' => 'before()']) );

        // Этот метод можно вызвать только один раз
        self::ensureLogic(!$this->dispatcher, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_EXCEPTION', ['#METHOD#' => 'dispatcher()']) );

        $this->dispatcher = true;

        $targetId = $this->getId('target');
        $dispatcherId = $this->getId('dispatcher');

        return  "id=\"$dispatcherId\"";
    }

    public function target(): string
    {
        // Этот метод может быть вызван только после begin
        self::ensureLogic($this->dispatcher, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_BEFORE_EXCEPTION', ['#METHOD#' => 'dispatcher()']) );

        // Этот метод можно вызвать только один раз
        self::ensureLogic(!$this->target, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_EXCEPTION', ['#METHOD#' => 'target()']) );

        $this->target = true;

        $targetId = $this->getId('target');

        return "id=\"$targetId\"";
    }

    public function end()
    {
        // Этот метод может быть вызван только после begin
        self::ensureLogic($this->target, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_BEFORE_EXCEPTION', ['#METHOD#' => 'target()']) );

        // Этот метод можно вызвать только один раз
        self::ensureLogic(!$this->end, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_EXCEPTION', ['#METHOD#' => 'end()']) );

        $this->end = true;

        $targetId = $this->getId('target');
        $dispatcherId = $this->getId('dispatcher');

        $event = $this->event;

        // Подключим jQuery
        \CJSCore::Init(array("jquery"));

        ob_start();
        ?>
        <script>
            $('#<?= $dispatcherId ?>').on('<?= $event ?>', function() {
                $('#<?= $targetId ?>').toggle();
            });
        </script>
        <?php

        return ob_get_clean();
    }
}