<?php

return [
    '_security' => [
        'owner' => 'admin',
        'actions' => [
            'admin' => ['create', 'get', 'update', 'delete'],
            'person' => ['create', 'get', 'update', 'delete']
        ]
    ],
    'create' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'hidden', 'value'=>'dynamic_properties'],
                'action_name'=>['type'=>'hidden', 'value'=>'create'],
                'name'=>['type'=>'text'],
                'description'=>['type'=>'text'],
                'type'=>['type'=>'text', 'value'=>'2'],
                'e_user_id'=>['type'=>'text'],
                'e_entity_class_id'=>['type'=>'text']
            ]
        ],
        'properties' => [
            'user_id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_user_id']]],
            'entity_class_id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_entity_class_id']]]
        ],
        'rules' => [],
        'action' => [
            'form', ['send']
        ],
        'callback' => [
            'success'=> ['console'],
            'errors'=> 'alert'
        ]
    ],
    'update' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'hidden', 'value'=>'dynamic_properties'],
                'action_name'=>['type'=>'hidden', 'value'=>'update'],
                'e_id'=>['type'=>'hidden'],
                'e_user_id'=>['type'=>'hidden'],
                'e_entity_class_id'=>['type'=>'hidden'],
                'type'=>['type'=>'hidden'],
                'type_name'=>['type'=>'hidden'],
                'name'=>['type'=>'text'],
                'description'=>['type'=>'text'],
                'the_date'=>['type'=>'hidden']
            ]
        ],
        'properties' => [
            'id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_id']]],
            'user_id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_user_id']]],
            'entity_class_id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_entity_class_id']]]
        ],
        'rules' => [],
        'action' => [
            'form', ['send']
        ],
        'callback' => [
            'success'=> ['reload'],
            'errors'=> 'alert'
        ]
    ],
    'details' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'text', 'value'=>'dynamic_properties'],
                'action_name'=>['type'=>'text', 'value'=>'details'],
                'e_id'=>['type'=>'text'],
                'e_user_id'=>['type'=>'text']
            ]
        ],
        'properties' => [
            'name'=>[],
            'description'=>[],
            'the_date' => [],
            'type' =>[],
            'type_name' => ['modifiers_out' => ['rename' => ['source' => '#type']]],
            'e_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'id']]],
            'e_user_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'user_id']]],
            'e_entity_class_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'entity_class_id']]],
            'uuid'=>[],
            'id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_id']]],
            'user_id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_user_id']]]
        ],
        'rules' => [],
        'action' => [
            'form', ['send']
        ],
        'callback' => [
            'success'=> ['console'],
            'errors'=> 'alert'
        ]
    ],
    'delete' => [
        'form' => [
            'fields' => [
                'text'=>['type'=>'elem_text', 'text'=> 'Вы действительно хотите удалить свойство <b>{{name}}</b> ( <i>{{description}}</i> ) ?'],
                'name'=>['type'=>'hidden'],
                'description'=>['type'=>'hidden'],
                'entity'=>['type'=>'hidden', 'value'=>'dynamic_properties'],
                'action_name'=>['type'=>'hidden', 'value'=>'delete'],
                'e_id'=>['type'=>'hidden'],
                'e_user_id'=>['type'=>'hidden']
            ]
        ],
        'properties' => [
            'e_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'id']]],
            'e_user_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'user_id']]],
            'id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_id']]],
            'user_id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_user_id']]]
        ],
        'rules' => [],
        'action' => [
            'form', ['send']
        ],
        'callback' => [
            'success'=> ['console'],
            'errors'=> 'alert'
        ]
    ],
    'get_list' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'text', 'value'=>'dynamic_properties'],
                'action_name'=>['type'=>'text', 'value'=>'get_list'],
                'e_entity_class_id'=>['type'=>'text'],
                'pagination_page' => ['type'=>'text', 'value' => 1],
                'pagination_row_count' => ['type'=>'text', 'value' => 5]
            ]
        ],
        'properties' => [
            'name'=>[],
            'description'=>[],
            'the_date' => [],
            'type' =>[],
            'type_name' => ['modifiers_out' => ['rename' => ['source' => '#type']]],
            'e_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'id']]],
            'e_user_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'user_id']]],
            'e_entity_class_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'class_id']]],
            'entity_class_id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_entity_class_id']]],
            'uuid'=>[]
        ],
        'rules' => [],
        'action' => [
            'form', ['send']
        ],
        'callback' => [
            'success'=> ['jsgrid'],
            'errors'=> 'alert'
        ]
    ],
    'common' => [
        'app' => 'grid_view',
        'grid_view' => [
            'template' => '.grid-template-card:first',
            'title' => 'Pages',
            'settings' => [
                'paging' => true,
                'fields' =>[
                    [ 'name' => 'id', 'title' => 'ID',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'e_id', 'title' => 'EID',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'name', 'title' => 'Name',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'description', 'title' => 'Description',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'e_user_id', 'title' => 'e_user_id',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'e_owner_id', 'title' => 'e_owner_id',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'the_date', 'title' => 'Date',  'type' => 'text', 'width' => 50 ]
                ]
            ]
        ],
        'grid_edit' => [
            'template' => '.grid-template-card:first',
            'title' => 'Pages',
            'settings' => [
                'editing'=> true,
                'inserting'=> true,
                'sorting' => true,
                'paging' => true,
                'fields' =>[
                    [ 'name' => 'id', 'title' => 'ID',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'e_id', 'title' => 'EID',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'name', 'title' => 'Name',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'description', 'title' => 'Description',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'e_user_id', 'title' => 'e_user_id',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'e_owner_id', 'title' => 'e_owner_id',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'entity_class_id', 'title' => 'entity_class_id',  'type' => 'text', 'width' => 50 ],
                    [ 'name' => 'the_date', 'title' => 'Date',  'type' => 'text', 'width' => 50 ],
                    [ 'type'=> "control" ]
                ]
            ]
        ],
        'grid_edit_forms' => [
            'template' => '.grid-template-card:first',
            'title' => 'Dynamic properties',
            'context_menu_no_data' => [
                'items' => [
                    'create' => [
                        'name' => 'Create',
                        'icon' => 'fa-plus',
                        'action_data' => [
                            'string__entity' => 'dynamic_properties',
                            'string__action_name' => 'create',
                            'json_to_obj__action' => '["form",["modal"]]'
                        ]
                    ]
                ]
            ],
            'context_menu' => [
                'items' => [
                    'create' => [
                        'name' => 'Create',
                        'icon' => 'fa-plus',
                        'action_data' => [
                            'string__entity' => 'dynamic_properties',
                            'string__action_name' => 'create',
                            'json_to_obj__action' => '["form",["modal"]]'
                        ]
                    ],
                    'update' => [
                        'name' => 'Update',
                        'icon' => 'fa-edit',
                        'action_data' => [
                            'string__entity' => 'dynamic_properties',
                            'string__action_name' => 'update',
                            'json_to_obj__action' => '["form",["modal"]]'
                        ]
                    ],
                    'details' => [
                        'name' => 'Details',
                        'icon' => 'fa-eye',
                        'action_data' => [
                            'string__entity' => 'dynamic_properties',
                            'string__action_name' => 'details',
                            'json_to_obj__callback.success' => 
                                json_encode([
                                        'modal', 
                                        [
                                            'list',
                                            [
                                                'name' => 'Name',
                                                'description' => 'Description',
                                                'the_date' => 'Date',
                                                'type' => 'Type',
                                                'type_name' => 'Type name',
                                                'e_id' => 'ID',
                                                'e_entity_class_id' => 'Class ID'
                                            ]
                                        ]
                                    ])
                        ]
                    ],
                    'delete' => [
                        'name' => 'Delete',
                        'icon' => 'fa-remove',
                        'action_data' => [
                            'string__entity' => 'dynamic_properties',
                            'string__action_name' => 'delete',
                            'json_to_obj__action' => '["form",["modal"]]'
                        ]
                    ]
                ]
            ],
            'settings' => [
                'paging' => true,
                'fields' =>[
//                    ['name' => 'id', 'title' => 'ID',  'type' => 'text', 'width' => 50 ],
//                    ['name' => 'e_id', 'title' => 'EID',  'type' => 'text', 'width' => 50 ],
                    ['name' => 'name', 'title' => 'Name',  'type' => 'text', 'width' => 50 ],
                    ['name' => 'description', 'title' => 'Description',  'type' => 'text', 'width' => 50 ],
//                    ['name' => 'e_user_id', 'title' => 'e_user_id',  'type' => 'text', 'width' => 50 ],
//                    ['name' => 'e_owner_id', 'title' => 'e_owner_id',  'type' => 'text', 'width' => 50 ],
                    ['name' => 'the_date', 'title' => 'Date',  'type' => 'text', 'width' => 50 ],
//                    ['name' => 'uuid', 'title' => 'UUID',  'type' => 'text', 'width' => 50 ]
                ],
            ],
        ]
    ],
    
    'public_configuration_blocks' => ['grid_edit', 'grid_view', 'grid_edit_forms', 'get_list_form']
];
