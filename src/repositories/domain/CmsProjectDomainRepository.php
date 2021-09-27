<?php


namespace fl\cms\repositories\domain;

use yii;
use fl\cms\helpers\page\base\PageConstants;
use yii\base\Exception;

class CmsProjectDomainRepository
{
    /**
     * @param array $params
     * @return array
     * @throws Exception
     * @throws yii\db\Exception
     */
    public static function create(array $params, string $domain): array
    {
        $sqlParams = [
            ':CMS_PROJECT_ID' => (int)$params['cms_project_params']['id'],
            ':CMS_TREE_ID' => (int)$params['cms_tree_params']['id'],
            ':NAME' => $domain,
            ':CREATE_USER_ID' => (int)$params['user_id']
        ];
        $sql = "INSERT INTO cms_project_domain (cms_project_id, cms_tree_id, name, create_user_id)
                VALUES (:CMS_PROJECT_ID, :CMS_TREE_ID, :NAME, :CREATE_USER_ID);";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
        $sql = "SELECT 
                    *
                FROM cms_project_domain 
                ORDER BY id DESC 
                LIMIT 1;";
        return yii::$app->db->createCommand($sql)->queryOne();
    }
}