<?php

namespace fl\cms\external;

use yii;

class Api implements base\ApiInterfaces
{
    public static function exec(array $params)
    {
        $params['provider_config'] = require_once(yii::$app->params['fl_cms']['providers'][$params['provider']]['work_dir'] . '/base/config.php');
        $a = 0;
        if(isset($params['provider_config']['api']['actions'][$params['action']])){
            $class = $params['provider_config']['api']['actions'][$params['action']];
            $request = new $class();
            $request->send($params);
        }
        $a = 0;
        $a = 0;

    }
}