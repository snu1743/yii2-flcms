<?php


namespace fl\cms\repositories\project;

use yii;
use fl\cms\helpers\page\base\PageConstants;
use yii\base\Exception;

class CmsProjectTrees
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
                    ct.id AS cms_tree__id,
                    ct.cms_project_owner_id AS cms_tree__cms_project_owner_id,
                    cpd.name AS cms_project_domain__name
                FROM cms_project_tree_bind cptb
                LEFT JOIN cms_project cp on cp.id = cptb.cms_project_id
                LEFT JOIN cms_tree ct on cp.id = ct.cms_project_owner_id
                LEFT JOIN cms_project_domain cpd on cp.id = cpd.cms_project_id
                WHERE cpd.name = :DOMAIN";
        $result = yii::$app->db->createCommand($sql, $sqlParams)->queryAll();
        $pageData = [];
        if(is_array($result)) {
            foreach ($result as $keyItem => $item) {
                foreach ($item as $keyValue => $value){
                    $keys = explode('__', $keyValue);
                    if (count($keys) === 2) {
                        $pageData[$keyItem][$keys[0]][$keys[1]] = $value;
                    } else {
                        $pageData[$keyItem][$keys[0]] = $value;
                    }
                }
            }
        }
        return $pageData;
    }
}