<?php


namespace fl\cms\repositories\project;

use yii;
use fl\cms\helpers\page\base\PageConstants;
use yii\base\Exception;

class CmsProjectDetail
{
    /**
     * @param array $params
     * @return array
     * @throws Exception
     * @throws yii\db\Exception
     */
    public static function get(array $params):array
    {
        if (!isset($params['cms_project_domain'])) {
            throw new Exception('No required parameters CmsСhildPages::get');
        }
        $sqlParams = [
            ':DOMAIN' => (string)$params['cms_project_domain']
        ];
        $sql = "SELECT
                    cp.id AS cms_project__id,
                    cp.acronym AS cms_project__acronym,
                    cp.short_name AS cms_project__short_name,
                    cp.name AS cms_project__name,
                    cp.cms_project_status_id AS cms_project__cms_project_status_id,
                    cp.created_at AS cms_project__created_at,
                    cpd.name AS cms_project_domain__name,
                    cps.name AS cms_project_status__name,
                    cps.title AS cms_project_status__title,
                    ct.id AS cms_tree__id
                FROM cms_project cp
                LEFT JOIN cms_project_domain cpd on cp.id = cpd.cms_project_id
                LEFT JOIN cms_project_status cps on cp.cms_project_status_id = cps.id
                LEFT JOIN cms_tree ct on cpd.cms_tree_id = ct.id
                WHERE cpd.name = :DOMAIN";
        $result = yii::$app->db->createCommand($sql, $sqlParams)->queryOne();
        $pageData = [];
        if(is_array($result)) {
            foreach ($result as $keyValue => $value){
                $keys = explode('__', $keyValue);
                if (count($keys) === 2) {
                    $pageData[$keys[0]][$keys[1]] = $value;
                } else {
                    $pageData[$keys[0]] = $value;
                }
            }
        }
        return $pageData;
    }
}