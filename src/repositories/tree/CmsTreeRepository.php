<?php


namespace fl\cms\repositories\tree;

use yii;
use yii\base\Exception;
use fl\cms\helpers\page\base\PageConstants;

class CmsTreeRepository
{
    /**
     * @param array $params
     * @return array
     * @throws Exception
     * @throws yii\db\Exception
     */
    public static function create(array $params): array
    {
        $sqlParams = [
            ':CMS_PROJECT_OWNER_ID' => (int)$params['cms_project_params']['id'],
            ':CREATE_USER_ID' => (int)$params['user_id']
        ];
        $sql = "INSERT INTO cms_tree (cms_project_owner_id, create_user_id)
                VALUES (:CMS_PROJECT_OWNER_ID, :CREATE_USER_ID);";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
        $sql = "SELECT 
                    *
                FROM cms_tree 
                ORDER BY id DESC 
                LIMIT 1;";
        return yii::$app->db->createCommand($sql)->queryOne();
    }

    public static function setCmsProjectTreeBind(array $params): array
    {
        $sqlParams = [
            ':CMS_PROJECT_PROJECT_ID' => (int)$params['cms_project_params']['id'],
            ':CMS_PROJECT_TREE_ID' => (int)$params['cms_tree_params']['id'],
            ':CMS_PROJECT_TREE_BIND_STATUS_ID' => (int)$params['cms_project_tree_bind_status_id']
        ];
        $sql = "INSERT INTO cms_project_tree_bind (cms_project_id, cms_tree_id, cms_project_tree_bind_status_id)
                VALUES (:CMS_PROJECT_PROJECT_ID, :CMS_PROJECT_TREE_ID, :CMS_PROJECT_TREE_BIND_STATUS_ID);";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
        $sql = "SELECT 
                    *
                FROM cms_project_tree_bind 
                ORDER BY id DESC 
                LIMIT 1;";
        return yii::$app->db->createCommand($sql)->queryOne();
    }
}