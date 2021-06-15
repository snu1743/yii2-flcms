<?php


namespace fl\cms\controllers;

use Yii;
use fl\cms\controllers\base\CmsController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Class EntityController
 * @package fl\cms\controllers
 */
class EntityController extends CmsController
{
    public function actions()
    {
        return [
            'get-config' => [
                'class' => 'fl\cms\entities\Main',
            ],
            'action' => [
                'class' => 'fl\cms\entities\Main',
            ]
        ];
    }
}
