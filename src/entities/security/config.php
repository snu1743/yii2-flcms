<?php

return [
    '_security' => [
        'owner' => 'admin',
        'actions' => [
            'admin' => ['login'],
            'person' => ['login']
        ]
    ],
    'login' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'hidden', 'value'=>'security'],
                'action_name'=>['type'=>'hidden', 'value'=>'login'],
                'email'=>['type'=>'text'],
                'pass'=>['type'=>'text']
            ]
        ],
        'properties' => [
            'token'=>['type'=>'text'],
        ],
        'rules' => [],
        'action' => [
            'form', ['modal']
        ],
        'callback' => [
            'success'=> ['console'],
            'errors'=> 'alert'
        ]
    ],
    'common' => [],
    'public_configuration_blocks' => []
];
