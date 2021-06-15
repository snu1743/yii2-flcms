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
                ]
            ]
        ]
    ]
];