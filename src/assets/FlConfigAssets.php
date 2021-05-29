<?php

namespace fl\cms\assets;

class FlConfigAssets
{
    public static $config = [
        'css' => [
            'fontawesome-free_css_all.min.css' => 'almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css',
            'icheck-bootstrap.min.css' => 'almasaeed2010/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
            'adminlte.min.css' => 'almasaeed2010/adminlte/dist/css/adminlte.min.css',
//            'font-awesome.min.css' => '/fl/assets/font-awesome/css/font-awesome.min.css',
            'jsgrid.min.css' => 'almasaeed2010/adminlte/plugins/jsgrid/jsgrid.min.css',
            'jsgrid-theme.min.css' => 'almasaeed2010/adminlte/plugins/jsgrid/jsgrid-theme.min.css',
//            'jquery.contextMenu.min' => '/fl/assets/jquery/jquery.contextMenu.min.css',
//            'jsoneditor.css' => '/fl/assets/jsoneditor/dist/jsoneditor.css',
//            'fl-ace-main.css' => '/fl/assets/ace/main.css'
        ],
        'js' => [
            'jquery.min.js' => 'almasaeed2010/adminlte/plugins/jquery/jquery.min.js',
            'bootstrap.bundle.min.js' => 'almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js',
            'adminlte.min.js' => 'almasaeed2010/adminlte/dist/js/adminlte.min.js',
            'fl_query' => (YII_ENV === 'prod') ? 'snu1743/yii2-flcms/src/assets/js/common/flQuery3.min.js' : 'snu1743/yii2-flcms/src/assets/js/common/flQuery3.js',
//            'fl_query_v2' => '/fl/js/common/flQuery-v2.js',
            'fl_actions' => (YII_ENV === 'prod') ? 'snu1743/yii2-flcms/src/assets/js/common/lActions.min.js' : 'snu1743/yii2-flcms/src/assets/js/common/flActions.js',
//            'security_main.js' => '/fl/modules/security/main.js',
//            'fl_pages' => '/fl/js/pages/flPages.js',
            'lodash.min' => 'snu1743/yii2-flcms/src/assets/js/lodash/lodash.min.js',
//            'jquery.contextMenu.min' => '/fl/assets/jquery/jquery.contextMenu.min.js',
//            'jquery.ui.position' => '/fl/assets/jquery/jquery.ui.position.js',
//            'src-min-noconflict_ace.js' => '/fl/assets/ace-builds/src-min-noconflict/ace.js',
//            'jsoneditor.js' => '/fl/assets/jsoneditor/dist/jsoneditor.js'
        ],
        'depends' => [
//            'YiiAsset' => 'yii\web\YiiAsset',
//            'BootstrapAsset' => 'yii\bootstrap\BootstrapAsset',
        ],
    ];
}
