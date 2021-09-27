<?php


namespace fl\cms\repositories;

use yii;


class CmsUpdate
{
    public static function setBody(int $id, array $params)
    {
        $sqlParams [':CMS_PAGE_ID'] = $id;
        $sqlParams [':BODY'] = $params['body'];
        $sql = "INSERT INTO cms_page_content (cms_page_id, body)
                VALUES (:CMS_PAGE_ID, :BODY)
                ON CONFLICT (cms_page_id) DO UPDATE 
                SET cms_page_id = excluded.cms_page_id, 
                    body = excluded.body;";
        return yii::$app->db->createCommand($sql, $sqlParams)->queryOne();
    }
}