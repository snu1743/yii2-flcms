<?php
return [
    //Пока не реализованно
    '_security' => [
        'owner' => 'admin',
        'actions' => [
            'admin' => ['create', 'update', 'delete'],
            'person' => ['create', 'update', 'delete']
        ]
    ],
    '_settings' => [
        'allow_auto_generation_properties' => true
    ],
    '_properties' => [
        //Тут резместить список свойств
    ],
    //Шаблон для конструктора если есть.
    'exec_method_28' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'text', 'value'=>'dynamic_instances'],
                'class_name'=>['type'=>'text', 'value'=>'exec_method'],
                'action_name'=>['type'=>'text', 'value'=>'exec_method_28'],
                'instance_id'=>['type'=>'text', 'value'=>'0'],
                'method_id'=>['type'=>'text', 'value'=>'28'],
                'name'=>['type'=>'text'],
                'description'=>['type'=>'text']
            ]
        ],
        'properties' => [],
        'rules' => [],
        'action' => [
            'form', ['send']
        ],
        'callback' => [
            'success'=> ['jsgrid'],
            'errors'=> 'alert'
        ]
    ],
    //Шаблон для метода update если есть.
    'exec_method_2' => [
        'form' => [
            'fields' => []
        ],
        'properties' => [],
        'rules' => [],
        'action' => [
            'form', ['send']
        ],
        'callback' => [
            'success'=> ['console'],
            'errors'=> 'alert'
        ]
    ],
    //Шаблон для метода delete если есть.
    'exec_method_3' => [
        'form' => [
            'fields' => []
        ],
        'properties' => [],
        'rules' => [],
        'action' => [
            'form', ['send']
        ],
        'callback' => [
            'success'=> ['console'],
            'errors'=> 'alert'
        ]
    ],
    'common' => [
        'app' => 'grid_edit_forms',
        'grid_edit_forms' => [
            'template' => '.grid-template-card:first',
            'title' => 'Entities name',
            'context_menu_no_data' => [
                'items' => [
                    /**
                     * Эти методы должны соответствовать реально существующим и подходящими
                     * для контекстного меню. Пока лепим сюда все доступные методы.
                     * Замениеть "entities_name(имя сущности) на реальное"
                     */
                    'create' => [
                        'name' => 'Create',
                        'icon' => 'fa-plus',
                        'action_data' => [
                            'string__entity' => 'dynamic_instances',
                            'string__action_name' => 'exec_method_28',
                            'json_to_obj__action' => '["form",["modal"]]'
                        ]
                    ]
                ]
            ],
            'context_menu' => [
                'items' => [
                    /**
                     * Эти методы должны соответствовать реально существующим и подходящими
                     * для контекстного меню. Пока лепим сюда все доступные методы.
                     * Замениеть "entities_name(имя сущности) на реальное"
                     */
                    'create' => [
                        'name' => 'Create',
                        'icon' => 'fa-plus',
                        'action_data' => [
                            'string__entity' => 'dynamic_instances',
                            'string__action_name' => 'exec_method_28',
                            'json_to_obj__action' => '["form",["modal"]]'
                        ]
                    ],
                    'update' => [
                        'name' => 'Update',
                        'icon' => 'fa-edit',
                        'action_data' => [
                            'string__entity' => 'entities_name(имя сущности)',
                            'string__action_name' => 'exec_method_2',
                            'json_to_obj__action' => '["form",["modal"]]'
                        ]
                    ],
                    'delete' => [
                        'name' => 'Delete',
                        'icon' => 'fa-remove',
                        'action_data' => [
                            'string__entity' => 'entities_name(имя сущности)',
                            'string__action_name' => 'exec_method_3',
                            'json_to_obj__action' => '["form",["modal"]]'
                        ]
                    ]
                ]
            ],
            'settings' => [
                'fields' =>[],
            ],
        ]
    ],
    'public_configuration_blocks' => ['grid_edit_forms']
];
