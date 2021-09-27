<?php


namespace fl\cms\repositories\page;

use yii;
use fl\cms\helpers\page\PageConstants;
use fl\cms\helpers\cms\CmsConstants;
use fl\cms\helpers\actions\ActionsConstants;
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
        $projectId = 1;
        $sqlParams = [
            ':CMS_PAGE_ID' => (int)$params['cms_page_id'],
            ':USER_ID' => (int)$params['user_id'],
            ':ACTION_ID' => ActionsConstants::ACTION_PAGE_VIEW,
            ':OBJECT_TYPE_ID' => CmsConstants::OBJECT_TYPE_PAGE,
            ':IS_ACTIVE' => PageConstants::PAGE_STATUS_ACTIVE,
            ':TREE_ID' => (int)$params['cms_main_tree_id'],
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
        $groupIdsList = '';
        if ($groupStatus) {
            $groupIdsList = ',' . implode(",", $params['group_ids']);;
        }
        $sql = "SELECT
                       cp.id   AS cms_page__id,
                       cp.path AS cms_page__path,
                       cp.name AS cms_page__name
                FROM cms_page cp
                     LEFT JOIN cms_access_rule car on cp.id = car.cms_access_object_id
                WHERE cp.parent_id = :CMS_PAGE_ID
                    AND cp.is_active = :IS_ACTIVE
                    AND cp.cms_tree_id = :TREE_ID
                    AND 0 != (
                            SELECT
                            (
                                count(*)
                            ) as result
                            FROM cms_access_rule car
                            WHERE car.cms_access_object_id = cp.id
                            AND car.cms_access_object_type_id = :OBJECT_TYPE_ID
                            AND car.cms_object_action_id = :ACTION_ID
                            AND (car.user_id = :USER_ID OR car.cms_group_id IN (0$groupIdsList))
                        )
                GROUP BY cp.id, cp.path, cp.name
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