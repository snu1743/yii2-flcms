<?php


namespace fl\cms\repositories\page;

use yii;
use fl\cms\helpers\cms\CmsConstants;

class Content
{
    /**
     * @param array $params
     * @return array
     * @throws yii\db\Exception
     */
    public static function get(array $params): array
    {
        $sqlParams = [
            ':ID' => (int)$params['cms_page_id'],
            ':USER_ID' => (int)$params['user_id'],
            ':OBJECT_TYPE_ID' => CmsConstants::OBJECT_TYPE_PAGE,
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
                    cp.id AS cms_page__id,
                    cp.parent_id AS cms_page__parent_id,
                    cp.owner_id AS cms_page__owner_id,
                    cp.is_active  AS cms_page__is_active,
                    cp.path  AS cms_page__path,
                    cp.name  AS cms_page__name,
                    cp.title  AS cms_page__title,
                    cp.cms_tree_id  AS cms_page__tree_id,
                    cpc.body AS cms_page_content__body,
                    cplcb.cms_page_layout_id AS cms_page_layout_content_bind__cms_page_layout_id,
                    cplcb.cms_page_content_id AS cms_page_layout_content_bind__cms_page_content_id,
                    cplcb.version AS cms_page_layout_content_bind__version,
                    (
                        SELECT
                            json_build_object(
                                    'allowed_action_id_list',  json_agg(car.cms_object_action_id),
                                    'allowed_action_name_list',  json_agg(coa.name)
                                )
                        FROM cms_access_rule car
                                 LEFT JOIN cms_object_action coa on car.cms_object_action_id = coa.id
                        WHERE car.cms_access_object_id = :ID
                          AND car.cms_access_object_type_id = :OBJECT_TYPE_ID
                          AND (car.user_id = :USER_ID OR car.cms_group_id IN (0 $groupIdsList ))
                    ) AS cms_page__access_rules
                FROM cms_page cp
                    LEFT JOIN cms_page_layout_content_bind cplcb ON cp.id = cplcb.cms_page_id
                    LEFT JOIN cms_page_content cpc ON cpc.id = cplcb.cms_page_content_id
                    LEFT JOIN cms_page_layout cpl ON cpl.id = cplcb.cms_page_layout_id
                WHERE cp.id = :ID";
        $result = yii::$app->db->createCommand($sql, $sqlParams)->queryOne();
        $result['cms_page__access_rules'] = json_decode($result['cms_page__access_rules'], true);
        $pageData = [];
        if (is_array($result)) {
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

    /**
     * @param array $params
     * @return array
     * @throws yii\db\Exception
     */
    public static function insert(array $params): array
    {
        $sqlParams = [
            ':BODY' => (string)$params['body'],
            ':NAME' => isset($params['name']) ? (string)$params['name'] : '',
            ':DESCRIPTION' => isset($params['description']) ? (string)$params['description'] : '',
            ':CMS_PROJECT_ID' => (string)$params['cms_project_id'],
            ':CREATE_USER_ID' => (string)$params['user_id'],
        ];
        $sql = "INSERT INTO cms_page_content (body, name, description, cms_project_id, create_user_id)
                VALUES (:BODY, :NAME, :DESCRIPTION, :CMS_PROJECT_ID, :CREATE_USER_ID);";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
        $sql = "SELECT 
                    *
                FROM cms_page_content 
                ORDER BY id DESC 
                LIMIT 1";
        return yii::$app->db->createCommand($sql)->queryOne();
    }

    /**
     * @param array $params
     * @return array
     */
    public static function bind(array $params): array
    {
        $sqlParams = [
            ':CMS_PAGE_ID' => (int)$params['cms_page_id'],
            ':CMS_PAGE_CONTENT_ID' => (int)$params['cms_page_content_id'],
            ':VERSION' => (int)$params['version']
        ];
        $sql = "INSERT INTO cms_page_layout_content_bind (cms_page_id, cms_page_content_id, version)
                VALUES (:CMS_PAGE_ID, :CMS_PAGE_CONTENT_ID, :VERSION)";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
        $sql = "SELECT 
                    *
                FROM cms_page_layout_content_bind 
                WHERE cms_page_id = :CMS_PAGE_ID
                AND cms_page_content_id = :CMS_PAGE_CONTENT_ID
                AND version = :VERSION
                LIMIT 1";
        return yii::$app->db->createCommand($sql, $sqlParams)->queryOne();
    }

    /**
     * @param array $params
     * @return array
     * @throws yii\db\Exception
     */
    public static function update(array $params): array
    {
        $sqlParams = [
            ':ID' => (string)$params['page_data']['cms_page_content_id'],
            ':BODY' => (string)$params['body']
        ];
        $sql = "UPDATE 
                    cms_page_content 
                SET body = :BODY 
                WHERE id = :ID";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
        $sql = "SELECT 
                    *
                FROM cms_page_content 
                ORDER BY id DESC 
                LIMIT 1;";
        return yii::$app->db->createCommand($sql)->queryOne();
    }
}