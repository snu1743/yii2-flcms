<?php


namespace fl\cms\repositories\group;

use yii;
use fl\cms\helpers\page\base\PageConstants;
use yii\base\Exception;

class CmsGroupRepository
{
    const PROJECT_ADMINS_GROUP = 'admin';
    const PROJECT_USERS_GROUP = 'user';
    const PROJECT_ADMIN_GROUP_DESCRIPTION = 'Group of project administrators';
    const PROJECT_USER_GROUP_DESCRIPTION = 'Group of project users';

    /**
     * @param array $params
     * @return array
     * @throws yii\db\Exception
     */
    public static function create(array $params): array
    {
        $sqlParams = [
            ':PROJECT_OWNER_ID' => (int)$params['cms_project_params']['id'],
            ':NAME' => $params['group_name'],
            ':TITLE' => $params['group_title'],
            ':DESCRIPTION' => $params['group_description'] . ' ' . (string)$params['cms_project_params']['name'],
            ':CREATE_USER_ID' => (int)$params['user_id']
        ];
        $sql = "INSERT INTO cms_group (cms_project_owner_id, name, title, description, create_user_id)
                VALUES (:PROJECT_OWNER_ID, :NAME, :TITLE, :DESCRIPTION, :CREATE_USER_ID);";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
        $sql = "SELECT 
                    *
                FROM cms_group 
                ORDER BY id DESC 
                LIMIT 1;";
        return yii::$app->db->createCommand($sql)->queryOne();
    }

    /**
     * @param array $params
     * @return array
     * @throws yii\db\Exception
     */
    public static function setCmsProjectGroupBind(array $params, $group): array
    {
        $sqlParams = [
            ':PROGECT_ID' => (int)$params['cms_project_params']['id'],
            ':GROUP_ID' => (string)$group['id']
        ];
        $sql = "INSERT INTO cms_project_group_bind (cms_project_id, cms_group_id)
                VALUES (:PROGECT_ID, :GROUP_ID)";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
        $sql = "SELECT 
                    *
                FROM cms_project_group_bind 
                ORDER BY id DESC 
                LIMIT 1;";
        return yii::$app->db->createCommand($sql)->queryOne();
    }

    /**
     * @param array $params
     * @return array
     * @throws yii\db\Exception
     */
    public static function setCmsGroupUserBind(array $params, $group): array
    {
        $sqlParams = [
            ':GROUP_ID' => (int)$group['id'],
            ':USER_ID' => (int)$params['user_id'],
        ];
        $sql = "INSERT INTO cms_group_user_bind (cms_group_id, user_id)
                VALUES (:GROUP_ID, :USER_ID);";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
        $sql = "SELECT 
                    *
                FROM cms_group_user_bind 
                ORDER BY id DESC 
                LIMIT 1;";
        return yii::$app->db->createCommand($sql)->queryOne();
    }
}