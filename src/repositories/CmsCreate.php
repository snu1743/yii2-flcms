<?php


namespace fl\cms\repositories;

use yii;


class CmsCreate
{
    public const ROLE_TYPE_ID_USER = 1;
    public const ROLE_TYPE_ID_GROUP = 2;

    /**
     * Запрос для создания страницы CMS
     * @param array $params
     * @return mixed
     */
    public static function page(array $params)
    {
        $path = (string)$params['path'];
        $sqlParams [':PATH'] = $path;
        $sqlParams [':HASH_ID'] = (string)$params['path'] . 'a69226f22702d07021eba7a6eff836ff';
        $sqlParams [':PARENT_ID'] = (int)$params['parent_id'];
        $sqlParams [':OWNER_ID'] = (int)$params['owner_id'];
        $sqlParams [':TREE_ID'] = (int)$params['tree_id'];
        $sqlParams [':LEVEL'] = (int)$params['level'];
        $sqlParams [':IS_ACTIVE'] = (bool)$params['is_active'];
        $sql = "INSERT INTO cms_page (path, hash_id, parent_id, owner_id, tree_id, level, is_active, path_length)
                VALUES (:PATH, md5(:HASH_ID), :PARENT_ID, :OWNER_ID, :TREE_ID, :LEVEL, :IS_ACTIVE, LENGTH('$path')) RETURNING id;";
        return yii::$app->db->createCommand($sql, $sqlParams)->queryOne();
    }
}