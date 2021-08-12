<?php


namespace fl\cms\repositories\user;

use yii;
use yii\base\Exception;
use fl\cms\helpers\page\base\PageConstants;

class CmsUserRepository
{

    public static function setCmsProjectUserBind(array $params): array
    {
        $sqlParams = [
            ':CMS_PROJECT_PROJECT_ID' => (int)$params['cms_project_params']['id'],
            ':USER_ID' => (int)$params['user_id']
        ];
        $sql = "INSERT INTO cms_project_user_bind (cms_project_id, user_id)
                VALUES (:CMS_PROJECT_PROJECT_ID, :USER_ID);";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
        $sql = "SELECT 
                    *
                FROM cms_project_user_bind 
                ORDER BY id DESC 
                LIMIT 1;";
        return yii::$app->db->createCommand($sql)->queryOne();
    }
}