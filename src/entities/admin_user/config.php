<?php

return [
    'get' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'text', 'value'=>'admin_user'],
                'action_name'=>['type'=>'text', 'value'=>'get'],
                'pagin_page' => ['type'=>'text', 'value' => 0],
                'pagin_limit' => ['type'=>'text', 'value' => 5]
            ]
        ],
        'properties' => [
            'id'=> ['type'=>'number'],
            'firstname'=> ['type'=>'string'],
            'lastname'=> ['type'=>'string'],
            'email'=> ['type'=>'email']
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
    'create' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'text', 'value'=>'admin_user'],
                'action_name'=>['type'=>'text', 'value'=>'create']
            ]
        ],
        'rules' => [],
        'action' => [
            'sendForm', 'config', 'form'
        ],
        'callback' => [
            'success'=> 'alert',
            'errors'=> 'alert'
        ]
    ],
    'delete' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'text', 'value'=>'admin_user'],
                'action_name'=>['type'=>'text', 'value'=>'delete']
            ]
        ],
        'rules' => [],
        'action' => [
            'sendForm', 'config', 'form'
        ],
        'callback' => [
            'success'=> 'alert',
            'errors'=> 'alert'
        ]
    ],
    'common' => [
        'app' => 'grid_view',
        'grid_view' => [
            'template' => '.grid-template-card:first',
            'title' => 'Admin user',
            'settings' => [
                'paging' => true,
                'fields' =>[
                    [ 'name'=> "id", 'type'=> "number", 'width'=> 50 ],
                    [ 'name'=> "firstname", 'type'=> "text", 'width'=> 150 ],
                    [ 'name'=> "lastname", 'type'=> "text", 'width'=> 150 ],
                    [ 'name'=> "email", 'type'=> "text", 'width'=> 150 ],
                    [ 'type'=> "control" ]
                ]
            ]
        ]
    ],
    'public_configuration_blocks' => ['grid_edit', 'grid_view']
];
