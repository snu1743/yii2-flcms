<?php

return [
    'instances_list' => [
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
//            'name'=>[],
//            'description'=>[],
//            'the_date' => [],
//            'type' =>[],
//            'type_name' => ['modifiers_out' => ['rename' => ['source' => '#type']]],
//            'e_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'id']]],
//            'e_user_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'user_id']]],
//            'e_entity_class_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'entity_class_id']]],
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
    ],
    'instance_details' => [
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
    ],
    'get_constructors' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'text', 'value'=>'dynamic_instances'],
                'action_name'=>['type'=>'text', 'value'=>'get_constructors'],
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
            'e_entity_class_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'entity_class_id']]],
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
    'get_available_methods' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'text', 'value'=>'dynamic_instances'],
                'action_name'=>['type'=>'text', 'value'=>'get_available_methods'],
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
            'e_entity_class_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'entity_class_id']]],
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
    'public_configuration_blocks' => ['grid_edit_forms']
];
