<?php

return [
    'create' => [
        'form' => [
            'fields' => [
                'entity'=>['type'=>'text', 'value'=>'page'],
                'action_name'=>['type'=>'text', 'value'=>'create'],
                'alias'=>['type'=>'text', 'label'=>'Alias', 'attr'=>['placeholder'=>'Set alias']],
                'title'=>['type'=>'text', 'label'=>'Title', 'attr'=>['placeholder'=>'Set title']],
                'parent_id'=>['type'=>'text'],
            ]
        ],
        'grid' => [
            'columns' => [
                
            ]
        ],
        'rules' => [
            [['title', 'alias', 'parent_id'], 'required'],
            ['parent_id', 'integer', 'min' => 1, 'max' => 999999999],
            ['nav_container_id', 'integer', 'min' => 1, 'max' => 99999999],
            ['lang_id', 'integer', 'min' => 1, 'max' => 99],
            ['layout_id', 'integer','min' => 0, 'max' => 999999999],
            ['is_draft', 'integer', 'min' => 0, 'max' => 1],
            ['from_draft_id', 'integer', 'min' => 0, 'max' => 999999999],
            ['title', 'string', 'min' => 3, 'max' => 50],
            ['alias', 'string', 'min' => 3, 'max' => 50],
            ['description', 'string', 'min' => 3, 'max' => 4096]
        ],
        'action' => [
            'showModal', 'config', 'form'
        ],
        'callback' => [
            'success', 'alert',
            'error', 'alert'
        ]
    ],
    'delete' => [
        'fields' => [
            'entity'=>['type'=>'hidden', 'value'=>'page'],
            'action_name'=>['type'=>'hidden', 'value'=>'delete'],
            'action_confirm_label'=>['type'=>'label', 'value'=>'Вы хотите удалить страницу %label_text%?', 'reg' => '%label_text%', 'default'=>'Вы хотите удалить страницу?'],
            'id'=>['type'=>'hidden']
        ],
        'rules' => [
            ['id', 'integer', 'min' => 1, 'max' => 999999999]
        ]
    ]
];
