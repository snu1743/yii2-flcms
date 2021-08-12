<?php

return [
    '_security' => [],
    'create' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'hidden', 'value'=>'page'],
                'action_name'=>['type'=>'hidden', 'value'=>'create'],
                'name'=>['type'=>'text'],
                'alias'=>['type'=>'text'],
                'title'=>['type'=>'text'],
                'e_parent_cms_page'=>['type'=>'hidden']
            ]
        ],
        'properties' => [
            'parent_cms_page'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_parent_cms_page']]],
        ],
        'rules' => [],
        'action' => [
            'form', ['send']
        ],
        'callback' => [
            'success'=> ['pageReload'],
            'errors'=> 'alert'
        ]
    ],
    'edit' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'hidden', 'value'=>'page'],
                'action_name'=>['type'=>'hidden', 'value'=>'edit'],
//                'title'=>['type'=>'text'],
//                'alias'=>['type'=>'text'],
//                'e_parent_id'=>['type'=>'text']
            ]
        ],
        'properties' => [
//            'parent_id'=> ['modifiers_in' => ['decrypt' => ['source' => 'e_parent_id']]],
        ],
        'rules' => [],
        'action' => [
            'app', ['aceEditor']
        ],
        'callback' => [
            'success'=> ['aceEditor'],
            'errors'=> 'alert'
        ]
    ],
    'save' => [
        'form' => [
            'fields' => [
                'text'=>['type'=>'elem_text', 'text'=> 'Сохранить страницу?'],
                'entity'=>['type'=>'text', 'value'=>'page'],
                'action_name'=>['type'=>'text', 'value'=>'save'],
                'content'=>['type'=>'text'],
                'params'=>['type'=>'text'],
            ]
        ],
        'properties' => [
            'page_params'=> ['modifiers_in' => ['decrypt' => ['source' => 'params']]],
//            'content'=> ['modifiers_in' => ['decrypt' => ['source' => 'content']]],
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
    'common' => [
        'app' => 'ace_edit',
        'ace_edit' => [
            'data_source' => 'local_data',
            'title' => 'Page editor',
            'theme' => 'ace/theme/monokai',
            'mode' => 'ace/mode/html',
            'context_menu' => [
                'items' => [
                    'save' => [
                        'name' => 'Save',
                        'icon' => 'fa-plus',
                        'action_data' => [
                            'string__entity' => 'page',
                            'string__action_name' => 'save',
                            'json_to_obj__action' => '["form",["modal"]]'
                        ]
                    ]
                ]
            ],
            'settings' => []
        ],
    ],
    'public_configuration_blocks' => ['ace_edit']
];
