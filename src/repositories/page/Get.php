<?php


namespace fl\cms\repositories\page;

use yii;

class Get
{
    /**
     * @param string $path
     * @return array|null
     * @throws yii\db\Exception
     */
    public static function getPageAsPath(string $path): ?array
    {
        $sql = "SELECT
                    *
                FROM cms_page cp
                WHERE cp.path = :PATH
                AND cp.is_active = 1
                LIMIT 1";
        $return = yii::$app->db->createCommand($sql, [':PATH' => $path])->queryOne();
        return $return;
    }
}