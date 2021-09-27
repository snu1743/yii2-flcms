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
                'font-awesome.min.css',
                'jsgrid.min.css',
                'jsgrid-theme.min.css',
                'jquery.contextMenu.min',
                'jquery-ui.min',
                'fl-ace-main.css',
                'jsoneditor.min.css',
            ],
            'js' => [
                'jquery.min.js',
                'bootstrap.bundle.min.js',
                'adminlte.min.js',
                'jsgrid.min.js',
                'jquery.contextMenu.min',
                'jquery-ui.min',

                'src-min-noconflict_ace.js',
//                'theme-monokai.js',
                'jsoneditor.min.js',

                'fl_query',
                'fl_actions',
                'lodash.min'
            ],
            'depends' => []
        ]
    ];
}