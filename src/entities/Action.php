<?php

namespace fl\cms\entities;

use yii\base\ModelEvent;

class Action
{
    public function perform(string $class, string $action, array $params, bool $returnConfig) {
        $config = require_once __DIR__ . "/$class/config.php";
        $class = __NAMESPACE__ . "\\$class\\"  . ucfirst($action);
        new $class($action, $config, $params,  $returnConfig);
//        $model->initialization($action, $config, $params,  $returnConfig);
    }
}
