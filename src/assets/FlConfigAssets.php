<?php

namespace fl\cms\assets;

class FlConfigAssets
{
    public static $config = [
        'css' => [
            'fontawesome-free_css_all.min.css' => 'almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css',
            'icheck-bootstrap.min.css' => 'almasaeed2010/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
            'adminlte.min.css' => 'almasaeed2010/adminlte/dist/css/adminlte.min.css',
            'font-awesome.min.css' => 'snu1743/yii2-flcms/src/assets/css/font-awesome/font-awesome.min.css',
            'jsgrid.min.css' => 'almasaeed2010/adminlte/plugins/jsgrid/jsgrid.min.css',
            'jsgrid-theme.min.css' => 'almasaeed2010/adminlte/plugins/jsgrid/jsgrid-theme.min.css',
            'jquery.contextMenu.min' => 'snu1743/yii2-flcms/src/assets/css/jquery/jquery.contextMenu.min.css',
            'jquery-ui.min' => 'snu1743/yii2-flcms/src/assets/css/jquery/jquery-ui.min.css',
//            'jsoneditor.min.css' => 'snu1743/yii2-flcms/src/assets/ext/jsoneditor.min.css',
            'fl-ace-main.css' => 'snu1743/yii2-flcms/src/assets/ext/ace/main.css',
            'jsoneditor.min.css' => '/assets-ext/node_modules/jsoneditor/dist/jsoneditor.min.css'
        ],
        'js' => [
            'jquery.min.js' => 'almasaeed2010/adminlte/plugins/jquery/jquery.min.js',
            'bootstrap.bundle.min.js' => 'almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js',
            'jsgrid.min.js' => 'almasaeed2010/adminlte/plugins/jsgrid/jsgrid.min.js',
            'adminlte.min.js' => 'almasaeed2010/adminlte/dist/js/adminlte.min.js',
            'fl_query' => (YII_ENV === 'prod') ? 'snu1743/yii2-flcms/src/assets/js/common/flQuery3.min.js' : 'snu1743/yii2-flcms/src/assets/js/common/flQuery3.js',
//            'fl_query_v2' => '/fl/js/common/flQuery-v2.js',
            'fl_actions' => (YII_ENV === 'prod') ? 'snu1743/yii2-flcms/src/assets/js/common/lActions.min.js' : 'snu1743/yii2-flcms/src/assets/js/common/flActions.js',
//            'security_main.js' => '/fl/modules/security/main.js',
//            'fl_pages' => '/fl/js/pages/flPages.js',
            'lodash.min' => 'snu1743/yii2-flcms/src/assets/js/lodash/lodash.min.js',
            'jquery.contextMenu.min' => 'snu1743/yii2-flcms/src/assets/js/jquery/jquery.contextMenu.min.js',
            'jquery-ui.min' => 'snu1743/yii2-flcms/src/assets/js/jquery/jquery-ui.min.js',
            'src-min-noconflict_ace.js' => '/assets-ext/ace-builds/src-min-noconflict/ace.js',
            'jsoneditor.min.js' => '/assets-ext/node_modules/jsoneditor/dist/jsoneditor.min.js',
//            'theme-monokai.js' => 'snu1743/yii2-flcms/src/assets/ext/ace/theme-monokai.js',
//            'jsoneditor.min.js' => 'snu1743/yii2-flcms/src/assets/ext/jsoneditor/jsoneditor.min.js'
        ],
        'depends' => [],
    ];
}
