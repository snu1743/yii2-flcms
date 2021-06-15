<?php


namespace fl\cms\repositories;

use yii;
use fl\cms\helpers\page\base\PageConstants;
use yii\base\Exception;

class CmsСhildPages
{
    /**
     * @param array $params
     * @return array
     * @throws Exception
     * @throws yii\db\Exception
     */
    public static function get(array $params):array
    {
        if (!isset($params['cms_page_id'])) {
            throw new Exception('No required parameters CmsСhildPages::get');
        }
        $sqlParams = [
            ':CMS_PAGE_ID' => (int)$params['cms_page_id'],
            ':USER_ID' => (int)$params['user_id'],
            ':CMS_PAGE_ACTION_ID' => PageConstants::ACTION_VIEW_ID,
            ':ROLE_TYPE_USER' => PageConstants::ROLE_TYPE_ID_USER
        ];
        $groupStatus = false;
        if (isset($params['group_ids']) && is_array($params['group_ids']) && count($params['group_ids']) > 0) {
            $groupStatus = true;
            foreach ($params['group_ids'] as $groupId) {
                if (!is_numeric($groupId)) {
                    $groupStatus = false;
                }
            }
        }
        $sqlGroup = '0';
        if ($groupStatus) {
            $sqlParams[':ROLE_TYPE_GROUP'] = PageConstants::ROLE_TYPE_ID_GROUP;
            $groupIdsList = implode(",", $params['group_ids']);;
            $sqlGroup = ",
                    (
                        SELECT
                            count(*)
                        FROM cms_page_access cpa
                        WHERE cpa.cms_page_id = :CMS_PAGE_ID
                        AND cpa.cms_page_action_id = :CMS_PAGE_ACTION_ID
                        AND cpa.role_type_id = :ROLE_TYPE_GROUP
                        AND cpa.role_id IN ($groupIdsList)
                    ) as group";
        }
        $sql = "SELECT
                    cp.id AS cms_page__id,
                    cp.path  AS cms_page__path
                FROM cms_page cp
                LEFT JOIN cms_page_access cpa on cp.id = cpa.cms_page_id
                WHERE cp.parent_id = :CMS_PAGE_ID
                AND cp.is_active = true
                AND 0 != (
                        SELECT
                        (
                            count(*) +
                            $sqlGroup
                        ) as result
                        FROM cms_page_access cpa
                        WHERE cpa.cms_page_id = cp.id
                        AND cpa.cms_page_action_id = :CMS_PAGE_ACTION_ID
                        AND cpa.role_type_id = :ROLE_TYPE_USER
                        AND cpa.role_id = :USER_ID
                    )
                GROUP BY cp.id, cp.path
                ORDER BY cp.path";
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