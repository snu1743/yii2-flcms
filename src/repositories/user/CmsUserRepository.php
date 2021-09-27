<?php


namespace fl\cms\repositories\user;

use yii;
use yii\base\Exception;
use fl\cms\helpers\page\base\PageConstants;

class CmsUserRepository
{
    public static function getUserDetailsForForSession(array $params): array
    {
        $sqlParams = [
            ':USER_ID' => (int)$params['user_id']
        ];
    }

    /**
     * @param array $params
     * @return array
     * @throws yii\db\Exception
     */
    public static function getCmsGroupUserBindIdList(array $params): array
    {
        $sqlParams = [
            ':USER_ID' => (int)$params['user_id']
        ];
        $sql = "SELECT 
                    gub.cms_group_id
                FROM cms_group_user_bind AS gub
                WHERE gub.user_id = :USER_ID";
        $result['items'] = yii::$app->db->createCommand($sql, $sqlParams)->queryAll();
        foreach ($result['items'] as $gub){
            $result['ids'][] = $gub['cms_group_id'];
        }
        return $result['ids'];
    }
}