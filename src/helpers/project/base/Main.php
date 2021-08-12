<?php


namespace fl\cms\helpers\project\base;

use yii;
use fl\cms\helpers\actions\ActionsConstants;
use fl\cms\helpers\cms\CmsConstants;
use fl\cms\helpers\user\Session;
use fl\cms\repositories\page\Get;
use yii\base\Exception;

/**
 * Class Main
 * @package fl\cms\helpers\project\base
 */
abstract class Main implements iProject
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';
    public $params = [];
    public $status = [];
    public $result = [];
    public $session;

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public static function exec(array $params): array
    {
        $class = get_called_class();
        /* @var $obj iProject */
        $obj = new $class();
        $validator = new Validator($obj->getRules(), $params);
        if (!$validator->exec()) {
            throw new Exception(json_encode($validator->getErrors(), JSON_UNESCAPED_UNICODE));
        };
        $obj->setParams($params);
        $obj->session = yii::$app->session;
        $obj->process();
        return $obj->getResult();
    }

    /**
     * @param array $params
     */
    public function setParams($params): void
    {
        $this->params = $params;
    }

    public function prepareParams(): void
    {

    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }
}