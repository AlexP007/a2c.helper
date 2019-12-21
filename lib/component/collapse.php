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
//    private $action;

    public function begin(array $params)
    {
        // Этот метод можно вызвать только один раз
        self::ensureLogic(!$this->begin, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_EXCEPTION', ['#METHOD#' => 'before()']) );

        $this->setTargetId();
        $this->setDispatcherId();

        $this->event = $params['EVENT'] ?? 'click';
//        $this->action = $params['ACTION'] ?? 'toggle';

        $this->begin = true;
    }

    public function dispatcher(): string
    {
        // Этот метод может быть вызван только после begin
        self::ensureLogic($this->begin, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_BEFORE_EXCEPTION', ['#METHOD#' => 'before()']) );

        // Этот метод можно вызвать только один раз
        self::ensureLogic(!$this->dispatcher, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_EXCEPTION', ['#METHOD#' => 'dispatcher()']) );

        $this->dispatcher = true;

        $dispatcherId = $this->getDispatcherId();

        return  "id=\"$dispatcherId\"";
    }

    public function target(): string
    {
        // Этот метод может быть вызван только после begin
        self::ensureLogic($this->dispatcher, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_BEFORE_EXCEPTION', ['#METHOD#' => 'dispatcher()']) );

        // Этот метод можно вызвать только один раз
        self::ensureLogic(!$this->target, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_EXCEPTION', ['#METHOD#' => 'target()']) );

        $this->target = true;

        $targetId = $this->getTargetId();

        return "id=\"$targetId\"";
    }

    public function end(): string
    {
        // Этот метод может быть вызван только после begin
        self::ensureLogic($this->target, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_BEFORE_EXCEPTION', ['#METHOD#' => 'target()']) );

        // Этот метод можно вызвать только один раз
        self::ensureLogic(!$this->end, Loc::getMessage('A2C_HELPER_COMPONENT_METHOD_EXCEPTION', ['#METHOD#' => 'end()']) );

        $this->end = true;

        $targetId = $this->getTargetId();
        $dispatcherId = $this->getDispatcherId();

        $event = $this->event;
        $callback = $this->getCallback();

        // Подключим jQuery
        \CJSCore::Init(array("jquery"));

        ob_start();
        ?>
        <script>
            $('#<?= $dispatcherId ?>').on('<?= $event ?>', function() {
                $('#<?= $targetId ?>').toggle();
                !<?= $callback ?>('<?= $targetId ?>', '<?= $dispatcherId ?>')
            });
        </script>
        <?php

        return ob_get_clean();

    }

    protected function setDispatcherId()
    {
        $this->setId('dispatcher');
    }

    protected function getDispatcherId()
    {
        return $this->getId('dispatcher');
    }

    protected function setTargetId()
    {
        $this->setId('target');
    }

    protected function getTargetId()
    {
        return $this->getId('target');
    }
}