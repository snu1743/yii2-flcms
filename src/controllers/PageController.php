<?php


namespace fl\cms\controllers;

use Yii;
use fl\cms\controllers\base\CmsController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Url;
use fl\cms\helpers\page\Create;
use fl\cms\helpers\page\View;
use fl\cms\views\base\ViewParams;
use fl\cms\helpers\url\UrlBase;

/**
 * Class CmsController
 * @package fl\cms\controllers
 */
class PageController extends CmsController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['view'],
                    'allow' => true,
                    'roles' => ['?', '@'],
                ],
                [
                    'actions' => ['edit'],
                    'allow' => true,
                    'roles' => ['@'],
                ]
            ]
        ];
        $behaviors['verbs'] = [
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
            'path' => UrlBase::getCurrentPath()
        ];
        $data = View::exec($params);
        $this->layout = '@vendor/snu1743/yii2-flcms/src/views/layouts/main';

        $page = $this->render('@vendor/snu1743/yii2-flcms/src/views/cms_page', ViewParams::set($data));
        $page = $this->initApps($page, $data);
        return $page;
    }

    public function actionEdit()
    {
        $params = [
            'path' => UrlBase::getCurrentPath()
        ];
        $data = View::exec($params);
        $this->layout = '@vendor/snu1743/yii2-flcms/src/views/layouts/main';
        $page = $this->render('@vendor/snu1743/yii2-flcms/src/views/cms_page_edit_mod_1', ViewParams::set($data));
        return $page;
    }
}
