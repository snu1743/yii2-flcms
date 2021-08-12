<?php


namespace fl\cms\repositories;

use yii;
use fl\cms\helpers\cms\CmsConstants;

class CmsView
{
    /**
     * @param array $params
     * @return array
     * @throws yii\db\Exception
     */
    public static function getPageData(array $params): array
    {
        $sqlParams = [
            ':ID' => (int)$params['cms_page_id']
        ];
        $sql = "SELECT
                    cp.id AS cms_page__id,
                    cp.parent_id AS cms_page__parent_id,
                    cp.owner_id AS cms_page__owner_id,
                    cp.is_active  AS cms_page__is_active,
                    cp.path  AS cms_page__path,
                    cp.name  AS cms_page__name,
                    cp.title  AS cms_page__title,
                    cp.cms_tree_id  AS cms_page__tree_id,
                    cpc.body AS cms_page_content__body
                FROM cms_page cp
                LEFT JOIN cms_page_content cpc on cp.id = cpc.cms_page_id
                WHERE cp.id = :ID";
        $result = yii::$app->db->createCommand($sql, $sqlParams)->queryOne();
        $pageData = [];
        if(is_array($result)) {
            foreach ($result as $key => $value) {
                $keys = explode('__', $key);
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