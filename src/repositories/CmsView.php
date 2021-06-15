<?php


namespace fl\cms\repositories;

use yii;

class CmsView
{
    /**
     * @param array $params
     * @return array
     */
    public static function getPageData(array $params): array
    {
        if (isset($params['id'])) {
            $sqlParams [':ID'] = $params['id'];
            $where = "WHERE cp.id = :ID";
        } elseif (isset($params['hash_id'])) {

        } else {
            if (strlen($params['path']) > 32) {
                exit('strlen($params[\'path\']) > 32');
            } else {
                $sqlParams [':PATH'] = $params['path'];
                $where = "WHERE cp.path = :PATH";
            }
        }
        $sql = "SELECT
                    cp.id AS cms_page__id,
                    cp.is_active  AS cms_page__is_active,
                    cp.path  AS cms_page__path,
                    cpc.body AS cms_page_content__body
                FROM cms_page cp
                LEFT JOIN cms_page_content cpc on cp.id = cpc.cms_page_id
                $where";
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