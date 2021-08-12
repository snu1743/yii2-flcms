<?php
return [

    'on '.yii\web\Application::EVENT_BEFORE_ACTION => [
        'fl\cms\helpers\cms\Main','init'
    ],

    'params' => [
        'fl_cms' => [
            'apps' => [
                'fl_cms_page_tree' => [
                    'class' => 'fl\cms\apps\items\page\tree\App'
                ],
                'fl_cms_page_breadcrumb' => [
                    'class' => 'fl\cms\apps\items\page\breadcrumb\App'
                ],
                'fl_cms_page_title' => [
                    'class' => 'fl\cms\apps\items\page\title\App'
                ]
            ],
            'domains' => [
                'main_domain' => 'freelemur.com'
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