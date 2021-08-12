<?php


namespace fl\cms\repositories\project;

use yii;
use fl\cms\helpers\page\base\PageConstants;
use yii\base\Exception;

class CmsProjectRepository
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
            ':ACRONYM' => (string)$params['acronym'],
            ':SHORT_NAME' => (string)$params['short_name'],
            ':NAME' => (string)$params['name'],
            ':CMS_PROJECT_STATUS_ID' => (int)$params['cms_project_status_id']
        ];
        $sql = "INSERT INTO cms_project (acronym, short_name, name, cms_project_status_id)
                VALUES (:ACRONYM, :SHORT_NAME, :NAME, :CMS_PROJECT_STATUS_ID);";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
        $sql = "SELECT 
                    *
                FROM cms_project 
                ORDER BY id DESC 
                LIMIT 1;";
        return yii::$app->db->createCommand($sql)->queryOne();
    }
}