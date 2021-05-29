<?php


namespace fl\cms\controllers\base;

use Yii;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\filters\auth\CompositeAuth;
//use yii\filters\auth\HttpBasicAuth;
//use yii\filters\auth\HttpBearerAuth;
//use yii\filters\auth\QueryParamAuth;
use fl\cms\helpers\auth\ETokenAuth;

/**
 * Class ApiController
 * @package fl\cms\controllers
 */
class ApiController extends ActiveController
{
    public $modelClass = 'common\models\User';
    protected $apiRequest;

    public function actions()
    {
        return [];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                ETokenAuth::class
            ],
        ];
        return $behaviors;
    }

    public function beforeAction($action) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $this->apiRequest = json_decode(Yii::$app->request->rawBody, true);
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
}
