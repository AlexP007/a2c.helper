<?php

namespace A2c\Helper\Admin;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Localization\Loc;
use CAdminList;

/**
 * Class CAdminListHelper
 * @package A2c\Helper\Admin
 *
 * Данный класс призван хоть немного разделить
 * код, который используется в админке для вывода
 * грида с фильтром.
 *
 * Его основная задача интегрировать сущность,
 * представленную на странице, со стандартным
 * битриксовым CAdminList. Однако все методы
 * последнего не хватило времени сюда инкапсулировать.
 * Поэтому часть вызовов осталось в коде админки.
 */
class CAdminListHelper
{
    private $entity;
    private $adminEntity;
    private $map = [];
    private $contextMenu;
    private $tableName;
    private $groupActions;
    private $adminList;
    private $headers = [];
    private $booleanFields = [];
    private $serializedFields = [];
    private $filterFields = [];
    private $filterLabels = [];
    private $editFields = [];
    private $filter = [];

    /**
     * Получаем сущность и инициализированный
     * CAdminList и устанавливаем базовые значения
     * todo: Инициализировать CAdminList в конструкторе
     *
     * EditTable constructor.
     * @param $entity
     * @param \CAdminList $adminList
     */
    public function __construct(DataManager $entity)
    {
        $this->entity = $entity;
        $this->setTableName();
        $this->setCAdminList();
        $this->setMap();
    }

    public function init()
    {

        $list= $this->adminList;

        $this->showChain();
        $list->AddAdminContextMenu($this->contextMenu);
        $list->InitFilter($this->filterFields); // todo: проерку переменных фильтра
        $list->AddGroupActionTable($this->groupActions);
        $list->DisplayList();
    }

    private function setMap()
    {
        $this->map = $this->entity::getMap();
    }

    private function setTableName()
    {
        $this->tableName = $this->entity::getTableName();
    }

    public function setGroupActions(array $groupActions)
    {
        $this->groupActions = $groupActions;
    }

    public function setCAdminList()
    {
        $this->adminList = new CAdminList($this->tableName);
    }

    public function addAdminContextMenu(array $contextMenu)
    {
        $this->contextMenu = $contextMenu;
    }

    public function setAdminEntity($entity)
    {
        $this->adminEntity = $entity;
    }

    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param array $order
     * @return mixed
     *
     * Данный метод возвращает список
     * отфильтрованных сущностей
     */
    public function getEntityList($order = array())
    {
        if (count($order)) {
            return $this->entity::getList([
                'filter' => $this->filter,
                'order' => $order
            ]);
        }

        return $this->entity::getList([
            'filter' => $this->filter,
        ]);
    }

    public function getCAdminFilter()
    {
        return new \CAdminFilter(
            $this->tableName . '_filter',
            $this->filterLabels
        );
    }
    /**
     * Инициализируем фильтр, получаем список всех полей сущности
     *
     * todo: переписать на композицию
     *
     * @param \CAdminList $lAdmin
     * @return array
     */
    public function setFilter(array $filterFields)
    {
       $this->filterFields = $filterFields;
    }

    public function addHeaders()
    {
        $this->adminList->AddHeaders($this->headers);
    }

    /**
     * Этот код нужно анализировать и декомпозировать
     * Если просто, то он реализует логику какие поля
     * разрешь редактировать, а какие нет
     *
     * todo:: избавится от глобальных переменных и переписать на композицию
     *
     * @param $primary
     * @param array $item
     * @return mixed
     */
    public function addRow($primary ,array $item)
    {
        $adminRow = $this->adminList->AddRow($primary, $item);

        $visibleHeaders = $this->adminList->GetVisibleHeaderColumns();

        if ($this->entity::isUsers() ) {
            foreach( [ 'CREATED_USER_ID', 'MODIFIED_USER_ID' ] as $columnId ) {
                if (in_array( $columnId, $visibleHeaders ) && (int) $item[ $columnId ] > 0) {
                    $adminRow->AddField($columnId, $this->adminEntity->formatUserProperty( $item[ $columnId ] ), true);
                }
            }
        }

        foreach ($this->booleanFields as $key ) {
            if (in_array( $key, $visibleHeaders ) && array_key_exists( $key, $item ) ) {
                $adminRow->AddField( $key, $item[ $key ] === 'Y' ? Loc::getMessage('KODIX_D7_ADMIN_BOOLEAN_YES') : Loc::getMessage('KODIX_D7_ADMIN_BOOLEAN_NO'), true);
            }
        }
        foreach ($this->editFields as $key ) {
            if (in_array( $key, $visibleHeaders ) && array_key_exists( $key, $item ) ) {
                $adminRow->AddField( $key, $item[$key], true);
            }
        }

        foreach ($this->serializedFields as $key ) {
            if (in_array( $key, $visibleHeaders ) && array_key_exists( $key, $item ) ) {
                // Вывод в список сериализованного свойства
                $column = $this->getMap[ $key ];

                if (array_key_exists( 'part_data_type', $column ) && is_array( $column['part_data_type']) ) {
                    $columnDefinition = array_keys( $column['part_data_type'] );

                    if (count( $columnDefinition ) === 1 && is_numeric( $columnDefinition[0]) ) {
                        $adminRow->AddField( $key, implode('<br />', $item[ $key ] ), true);
                    }
                    else if (count($columnDefinition) >= 1) {
                        $displayValue = [];

                        foreach ($item[ $key ] as $partValue) {
                            $showValues = [];

                            foreach ($column['part_data_type'] as $partKey => $dataType) {
                                $showValues[] = $partKey . ': ' . $partValue[ $partKey ];
                            }
                            $displayValue[] = implode(', ', $showValues);
                        }

                        $adminRow->AddField( $key, implode('<br />', $displayValue ), true);
                    }
                }
                else {
                    $adminRow->AddField( $key, '<pre>' . print_r( $item[ $key ], true ) . '</pre>', true );
                }
            }
        }
        return $adminRow;
    }

    private function showChain()
    {
        $this->adminList->ShowChain($this->adminList->CreateChain() );
    }
}
