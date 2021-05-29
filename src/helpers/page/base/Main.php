<?php


namespace fl\cms\helpers\page\base;

use yii\base\Exception;

/**
 * Class Main
 * @package fl\cms\helpers\page\base
 */
abstract class Main implements iPage
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';
    public $params = [];
    public $status = [];
    public $result = [];

    /**
     * @param array $params
     * @return array
     */
    public static function exec(array $params): array
    {
        $class = get_called_class();
        /* @var $obj iPage */
        $obj = new $class();
        $validator = new Validator($obj->getRules(), $params);
        if(!$validator->exec()){
            throw new Exception(json_encode($validator->getErrors(), JSON_UNESCAPED_UNICODE));
        };
        $obj->setParams($params);
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

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }
}