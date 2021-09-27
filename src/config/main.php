<?php
return [

    'on '.yii\web\Application::EVENT_BEFORE_ACTION => [
        'fl\cms\helpers\cms\Main','init'
    ],

    'params' => [
        'fl_cms' => [
            'apps' => [
                'fl_cms_pages_tree' => [
                    'class' => 'fl\cms\apps\items\pages\tree\App'
                ],
                'fl_cms_pages_breadcrumb' => [
                    'class' => 'fl\cms\apps\items\pages\breadcrumb\App'
                ],
                'fl_cms_pages_title' => [
                    'class' => 'fl\cms\apps\items\pages\title\App'
                ],
                'fl_cms_pages_menu_main' => [
                    'class' => 'fl\cms\apps\items\pages\menu\main\App'
                ]
            ],
            'domains' => [
                'main_domain' => 'http://snu1743.freelemur.com'
            ],
            'providers' => [
                'reg.ru' => [
                    'name' => 'Хостинг reg.ru',
                    'work_dir' => __DIR__ . '/../external/reg',
                ],
            ],
            'providers_settings' => [
                'prodaction' => 'reg.ru',
                'develop' => 'reg.ru',
            ]
        ]
    ]
];