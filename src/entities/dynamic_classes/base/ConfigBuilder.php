<?php


namespace fl\cms\entities\dynamic_classes\base;


use fl\cms\entities\Main;

final class ConfigBuilder
{
    /**
     * @var ConfigBuilder
     */
    private static $builder;
    private $config;
    private $entityClassId;

    public static function init(?array $config, int $entityClassId): array
    {
        self::$builder = new ConfigBuilder();
        return self::$builder->execute($config, $entityClassId);
    }

    /**
     * Создание конфига, управляющий(центральный) метод.
     * @param array|null $config
     * @param int $entityClassId
     * @return array
     */
    private function execute(?array $config, int $entityClassId): array
    {
        $this->config = $config;
        $this->entityClassId = $entityClassId;
//        try {
            $this->getMethodsList();
            $this->getClassProperties();
            $this->setDynamicInstancesCommonActions();
            $this->setMethodsList();
            $this->setCommon();
            $this->setPublicConfigurationBlocks();
//        } catch (\Exception $e) {
//            return [];
//        }



        return $this->config;
    }

    /**
     * Получает методы класса сущности.
     */
    private function getMethodsList(): void
    {
        $request = [
            'action_name' => 'methods_list',
            'entity' => 'dynamic_classes',
            'entity_class_id' => $this->entityClassId
        ];
        $result = Main::execute($request);
        $this->config['_methods_list'] = $result['properties'];
    }

    /**
     * Получаем свойства класса.
     */
    private function getClassProperties(): void
    {
        $request = [
            'action_name' => 'get_list',
            'entity' => 'dynamic_properties',
            'entity_class_id' => $this->entityClassId
        ];
        $result = Main::execute($request);;
        $this->config['_properties'] = $result['properties'];
        foreach ($this->config['_properties'] as $key => $property) {
            if(isset($property['name'])) {
                $this->config['_properties'][$property['name']] = $property;
                unset($this->config['_properties'][$key]);
            }
        }
    }

    /**
     * Добавляем настройки для общих(статических) методов класса.
     */
    private  function setDynamicInstancesCommonActions(): void
    {
        $this->setActionInstanceDetails();
        $this->setActionInstancesList();

    }

    /**
     * Добавляем настройки для общего (статического) метода instance_details.
     * TODO:Требуется доработать. Добавить метод setActionInstanceDetailsPropertiesDefault
     */
    private function setActionInstanceDetails(): void
    {
        $this->config['instance_details'] = [
            'form' => [
                'fields' => [
                    'entity'=>['type'=>'text', 'value'=>'dynamic_instances'],
                    'action_name'=>['type'=>'text', 'value'=>'instances_list'],
                    'e_entity_class_id'=>['type'=>'text'],
                    'pagination_page' => ['type'=>'text', 'value' => 1],
                    'pagination_row_count' => ['type'=>'text', 'value' => 5]
                ]
            ],
            'properties' => [
//            '#language' => [],
                'email' => [],
                'first_name' => [],
                'id' => [],
                'language' => [],
                'last_name' => [],
                'middle_name' => [],
                'state_id' => [],
                'the_date' => [],
//            'user_id' => [],
                'entity_class_id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_entity_class_id']]],
            ],
            'rules' => [],
            'action' => [
                'form', ['send']
            ],
            'callback' => [
                'success'=> ['jsgrid'],
                'errors'=> 'alert'
            ]
        ];
    }

    private function setActionInstancesList()
    {
        $this->config['instances_list'] = [
            'form' => [
                'fields' => [
                    'entity'=>['type'=>'text', 'value'=>'dynamic_instances'],
                    'action_name'=>['type'=>'text', 'value'=>'instances_list'],
                    'e_entity_class_id'=>['type'=>'text'],
                    'pagination_page' => ['type'=>'text', 'value' => 1],
                    'pagination_row_count' => ['type'=>'text', 'value' => 5]
                ]
            ],
            'properties' => $this->setActionInstancesListPropertiesDefault(),
            'rules' => [],
            'action' => [
                'form', ['send']
            ],
            'callback' => [
                'success'=> ['jsgrid'],
                'errors'=> 'alert'
            ]
        ];
    }

    private function setActionInstancesListPropertiesDefault(): array
    {
        $properties = [
            'entity_class_id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_entity_class_id']]],
            'e_entity_class_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'class_id']]],
            'e_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'id']]],
            'id'=> []
        ];
        foreach ($this->config['_properties'] as $prop) {
            if(!isset($prop['name'])) {
                continue;
            }
            $properties[$prop['name']] = [];
        }
        return $properties;
    }

    private function setMethodsList()
    {
        if(!is_array($this->config['_methods_list'])) {
            return;
        }
        foreach ($this->config['_methods_list'] as $key => $action) {
            if(!isset($action['name'])){
                continue;
            }
            $this->setAction($action);
            unset($this->config['_methods_list'][$key]);
        }
    }

    private function setAction(array $action)
    {
        $getTemplateFunc = 'getTemplateAction' . $action['name'];
        if(method_exists($this, $getTemplateFunc)) {
            $actionTemplate = $this->$getTemplateFunc();
        } else {
            $actionTemplate = $this->getTemplateActionDefault();
            $actionTemplate['form']['fields']['action_name']['value'] = strtolower($action['name']);
        }
        $this->config[strtolower($action['name'])] = $actionTemplate;
        $this->config['_methods_list'][strtolower($action['name'])] = $action;
    }

    private function getTemplateActionDefault(): array
    {
        return [
            'form' => [
                'fields' => $this->setFormFieldsDefault(),
            ],
            'properties' => [],
            'rules' => [],
            'action' => [
                'form', ['send']
            ],
            'callback' => [
                'success'=> ['reload'],
                'errors'=> 'console'
            ]
        ];
    }

    private function setFormFieldsDefault(): array
    {
        $fields = [
            'entity'=>['type'=>'hidden', 'value'=>'dynamic_instances'],
            'action_name'=>['type'=>'hidden', 'value'=>'']
        ];
        foreach ($this->config['_properties'] as $prop) {
            if(!isset($prop['name'])) {
                continue;
            }
            $fields[$prop['name']] = ['type'=>'text', 'value'=>''];
        }
        return $fields;
    }

    private function getTemplateActionCreate(): array
    {
        return [
            'form' => [
                'fields' => $this->setFormFieldsCreate()
            ],
            'properties' => [],
            'rules' => [],
            'action' => [
                'form', ['send']
            ],
            'callback' => [
                'success'=> ['reload'],
                'errors'=> 'console'
            ]
        ];
    }

    private function setFormFieldsCreate(): array
    {
        $fields = [
            'entity'=>['type'=>'hidden', 'value'=>'dynamic_instances'],
            'action_name'=>['type'=>'hidden', 'value'=>'create'],
            'e_entity_class_id'=>['type'=>'text', 'value'=>''],
            'e_id'=>['type'=>'text', 'value'=>'']
        ];
        foreach ($this->config['_properties'] as $prop) {
            if(!isset($prop['name'])) {
                continue;
            }
            $fields[$prop['name']] = ['type'=>'text', 'value'=>''];
        }
        return $fields;
    }

    private function getTemplateActionUpdate(): array
    {
        return [
            'form' => [
                'fields' => $this->setFormFieldsUpdate()
            ],
            'properties' => [
                'id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_id']]],
            ],
            'rules' => [],
            'action' => [
                'form', ['send']
            ],
            'callback' => [
                'success'=> ['reload'],
                'errors'=> 'console'
            ]
        ];
    }

    private function setFormFieldsUpdate(): array
    {
        $fields = [
            'entity'=>['type'=>'hidden', 'value'=>'dynamic_instances'],
            'action_name'=>['type'=>'hidden', 'value'=>'update'],
            'e_entity_class_id'=>['type'=>'text', 'value'=>''],
            'e_id'=>['type'=>'text', 'value'=>'']
        ];
        foreach ($this->config['_properties'] as $prop) {
            if(!isset($prop['name'])) {
                continue;
            }
            $fields[$prop['name']] = ['type'=>'text', 'value'=>''];
        }
        return $fields;
    }

    private function getTemplateActionDelete(): array
    {
        return [
            'form' => [
                'fields' => $this->setFormFieldsDelete()
            ],
            'properties' => [
                'id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_id']]],
            ],
            'rules' => [],
            'action' => [
                'form', ['send']
            ],
            'callback' => [
                'success'=> ['reload'],
                'errors'=> 'console'
            ]
        ];
    }

    private function setFormFieldsDelete(): array
    {
        $fields = [
            'entity'=>['type'=>'hidden', 'value'=>'dynamic_instances'],
            'action_name'=>['type'=>'hidden', 'value'=>'delete'],
            'text'=>['type'=>'elem_text', 'text'=> 'Вы действительно хотите удалить сущность?'],
            'e_entity_class_id'=>['type'=>'text', 'value'=>''],
            'e_id'=>['type'=>'text', 'value'=>'']
        ];
        return $fields;
    }

    private function setCommon()
    {
        $this->config['common'] = [
            'app' => 'grid_edit_forms',
            'grid_edit_forms' => [
                'template' => '.grid-template-card:first',
                'title' => 'Dynamic instances',
                'context_menu_no_data' => [
                    'items' => $this->setContextMenuNoDataItems()
                ],
                'context_menu' => [
                    'items' => $this->setContextMenuItems(),
                ],
                'settings' => [
                    'paging' => true,
                    'fields' =>$this->setGridEditForms(),
                ],
            ]
        ];
    }

    private function setGridEditForms()
    {
        $fields = [];
        foreach ($this->config['_properties'] as $key => $property) {
            if(isset($property['name'])) {
                $fields[] = ['name' => $property['name'], 'title' => $property['name'],  'type' => 'text', 'width' => 50 ];
            }
        }
        return $fields;
    }

    private function setContextMenuNoDataItems()
    {
        if(!is_array($this->config['_methods_list']) || !isset($this->config['_methods_list']['create'])) {
            return;
        }
        return [ 'create' =>
            [
            'name' => 'create',
            'icon' => 'fa-plus',
            'action_data' => [
                    'string__entity' => 'dynamic_instances',
                    'string__action_name' => 'create',
                    'json_to_obj__action' => '["form",["modal"]]'
                ]
            ]
        ];
    }

    private function setContextMenuItems()
    {
        if(!is_array($this->config['_methods_list'])) {
            return;
        }
        $menu = [];
        $item = [
            'name' => '',
            'icon' => 'fa-plus',
            'action_data' => [
                'string__entity' => 'dynamic_instances',
                'string__action_name' => '',
                'json_to_obj__action' => '["form",["modal"]]'
            ]
        ];
        foreach ($this->config['_methods_list'] as $key => $action) {
            if(!isset($action['name'])){
                continue;
            }
            $item['name'] = strtolower($action['name']);
            $item['action_data']['string__action_name'] = strtolower($action['name']);
            $menu[$key] = $item;
        }
        return $menu;
    }

    private function setPublicConfigurationBlocks()
    {
        $this->config['public_configuration_blocks'] = ['grid_edit_forms'];
    }

}