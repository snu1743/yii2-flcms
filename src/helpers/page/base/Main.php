<?php


namespace fl\cms\helpers\page\base;

use fl\cms\helpers\actions\ActionsConstants;
use fl\cms\helpers\cms\CmsConstants;
use fl\cms\helpers\user\Session;
use fl\cms\repositories\page\MainRepositories;
use yii;
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
    public $session;

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



    /**
     * @param string $path
     * @return int
     * @throws yii\db\Exception
     */
    public function getPageId(string $path, int $cmsMainTreeId): int
    {
        $result = MainRepositories::getPageAsPath($path, $cmsMainTreeId);
        if (!is_integer($result['id'])) {
            throw new \Exception('NotFound');
        };
        return $result['id'];
    }

    /**
     * @throws Exception
     * @throws yii\db\Exception
     */
    public function prepareParams()
    {
        $cmsMainTreeId = Session::getMainTreeId();
        if(!isset($this->params['cms_page_id']) || !is_integer($this->params['cms_page_id'])){
            $this->params['cms_page_id'] = $this->getPageId($this->params['path'], $cmsMainTreeId);
        }
        $this->params['cms_access_object_id'] = $this->params['cms_page_id'];
        $this->params['cms_access_object_type_id'] = CmsConstants::OBJECT_TYPE_PAGE;
        $this->params['user_id'] = Session::getUserId();
        $this->params['group_ids'] = Session::getGroupIds();
        $this->params['cms_project_id'] = Session::getProgectId();
        $this->params['cms_tree_id'] = Session::getMainTreeId();
    }

    /**
     * @throws Exception
     * @throws yii\db\Exception
     */
    public function prepareParamsCreate()
    {
        $cmsMainTreeId = Session::getMainTreeId();
        $this->params['cms_access_object_id'] = $this->params['cms_page_id'];
        $this->params['cms_access_object_type_id'] = CmsConstants::OBJECT_TYPE_PAGE;
        $this->params['user_id'] = Session::getUserId();
        $this->params['group_ids'] = Session::getGroupIds();
        $this->params['cms_project_id'] = Session::getProgectId();
        $this->params['cms_tree_id'] = Session::getMainTreeId();
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }
}