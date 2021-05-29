<?php


namespace fl\cms\controllers;

use Yii;
use fl\cms\controllers\base\ApiController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Url;
use fl\cms\helpers\page\Create;
use fl\cms\helpers\page\View;

/**
 * Class CmsController
 * @package fl\cms\controllers
 */
class PageController extends ApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['create', 'index'],
                    'allow' => true,
                    'roles' => ['?','@'],
                ]
            ],
        ];
        $behaviors['verbs']             = [
            'class' => VerbFilter::className(),
            'actions' => [
                'create' => ['post'],
            ],
        ];
        return $behaviors;
    }

    public function actionCreate()
    {
        Create::exec($this->apiRequest);
        return $this->asJson([]);
    }

    public function actionView()
    {
        $params = [
            'path' => trim(Url::to(), ' /')
        ];
        $data = View::exec($params);
        $this->layout = '@vendor/snu1743/yii2-flcms/src/views/layouts/main';
        return $this->render('@vendor/snu1743/yii2-flcms/src/views/cms_page',['data' => $data]);
    }
}
