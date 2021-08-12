<?php

return [
    '_security' => [    ],
    'create' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'hidden', 'value'=>'projects'],
                'action_name'=>['type'=>'hidden', 'value'=>'create'],
                'acronym'=>['type'=>'text'],
                'short_name'=>['type'=>'text'],
                'name'=>['type'=>'text'],
                'alias_primary'=>['type'=>'text'],
                'alias_secondary'=>['type'=>'text'],
            ]
        ],
        'properties' => [
            'parent_cms_page'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_parent_cms_page']]],
        ],
        'rules' => [
            [['acronym', 'short_name', 'name', 'alias_primary', 'alias_secondary'], 'required'],
            [['acronym'], 'string', 'min' => 2, 'max' => 10],
            [['short_name'], 'string', 'min' => 2, 'max' => 50],
            [['name'], 'string', 'min' => 2, 'max' => 250],
            [['alias_primary', 'alias_secondary'], 'string', 'min' => 2, 'max' => 50],
        ],
        'action' => [
            'form', ['send']
        ],
        'callback' => [
            'success'=> ['alert'],
            'errors'=> 'alert'
        ]
    ],
    'get_detail' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'text', 'value'=>'project'],
                'action_name'=>['type'=>'text', 'value'=>'get_detail'],
                'domain'=>['type'=>'text'],
                'e_id'=>['type'=>'text']
            ]
        ],
        'properties' => [
            'e_id'=> ['modifiers_out' => ['encrypt' => ['source' => 'id']]],
            'domain'=>[]
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
    'common' => [],
    'public_configuration_blocks' => []
];
