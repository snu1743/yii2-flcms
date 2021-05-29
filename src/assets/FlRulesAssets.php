<?php

namespace fl\cms\assets;

class FlRulesAssets
{
    public static $rules = [
        'default' => [
            'css' => [
                'fontawesome-free_css_all.min.css',
                'icheck-bootstrap.min.css',
                'adminlte.min.css',
//                'font-awesome.min.css',
                'jsgrid.min.css',
                'jsgrid-theme.min.css',
//                'jquery.contextMenu.min',
//                'fl-ace-main.css',
//                'jsoneditor.css',
////              'ionicons.min.css',
////              'AdminLTE.min.css',
////              '_all-skins.min.css',
////              'fl_css_main.css'
            ],
            'js' => [
                'jquery.min.js',
                'bootstrap.bundle.min.js',
                'adminlte.min.js',
//                'jquery.contextMenu.min',
//                'jquery.ui.position',
//                'src-min-noconflict_ace.js',
//                'jsoneditor.js',
////              'jquery.slimscroll.min.js',
////              'fastclick.js',
////              'storage_main.js',
////              'reconnecting-websocket.js',
////              'websocket_main.js',
                'fl_query',
//                'fl_query_v2',
////              'fl_pages',
                'fl_actions',
                'lodash.min',
////              'flMessages'
            ],
//            'depends' => ['YiiAsset']
        ],
//        'site/login' => [
//            'css' => ['default'],
//            'js' => ['default'],
//            'depends' => ['default']
//        ]
    ];
}