<?php


namespace fl\cms\repositories\page;

use yii;

class MainRepositories
{
    /**
     * Запрос для создания страницы CMS
     * @param array $params
     * @return mixed
     */
    public static function create(array $params)
    {
        $path = (string)$params['path'];
        $sqlParams [':PATH'] = $path;
        $sqlParams [':NAME'] = (string)$params['name'];
        $sqlParams [':TITLE'] = (string)$params['title'];
        $sqlParams [':HASH_ID'] = (string)$params['path'] . (string)$params['cms_tree_id'] . 'a69226f22702d07021eba7a6eff836ff';
        $sqlParams [':PARENT_ID'] = (int)$params['parent_id'];
        $sqlParams [':OWNER_ID'] = (int)$params['owner_id'];
        $sqlParams [':TREE_ID'] = (int)$params['cms_tree_id'];
        $sqlParams [':LEVEL'] = (int)$params['level'];
        $sqlParams [':IS_ACTIVE'] = (int)$params['is_active'];
        $sql = "INSERT INTO cms_page (path, name, title, hash_id, parent_id, owner_id, cms_tree_id, level, is_active, path_length)
                VALUES (:PATH, :NAME, :TITLE, md5(:HASH_ID), :PARENT_ID, :OWNER_ID, :TREE_ID, :LEVEL, :IS_ACTIVE, LENGTH('$path')) RETURNING id;";
        return yii::$app->db->createCommand($sql, $sqlParams)->queryOne();
    }

    /**
     * @param string $path
     * @return array|null
     * @throws yii\db\Exception
     */
    public static function getPageAsPath(string $path, int $cmsTreeId): ?array
    {
        $sql = "SELECT
                    *
                FROM cms_page cp
                WHERE cp.path = :PATH
                AND cp.is_active = 1
                AND cp.cms_tree_id = :CMS_TREE_ID
                LIMIT 1";
        $return = yii::$app->db->createCommand($sql, [':PATH' => $path, ':CMS_TREE_ID' => $cmsTreeId])->queryOne();
        return $return;
    }

    public static function delete(array $params)
    {
        $sqlParams = [
            ':PATH' => (string)$params['path'],
            ':PATH_CHILD' => (string)$params['path'] . '%',
            ':TREE_ID' => (string)$params['cms_tree_id']
        ];
        $sql = "DELETE FROM cms_page_content
                WHERE id IN (
                    SELECT cplcb.cms_page_content_id
                    FROM cms_page_layout_content_bind  AS cplcb
                    LEFT JOIN cms_page cp on (cp.path = :PATH OR cp.path LIKE :PATH_CHILD) AND cp.cms_tree_id = :TREE_ID
                    WHERE cplcb.cms_page_id  = cp.id
                )";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();

        $sql = "DELETE FROM cms_page_layout_content_bind AS cplcb
                WHERE cplcb.cms_page_id IN (
                    SELECT cp.id
                    FROM cms_page cp
                    WHERE (cp.path = :PATH OR cp.path LIKE :PATH_CHILD)
                    AND cp.cms_tree_id = :TREE_ID
                )";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();

        $sql = "DELETE FROM cms_page AS cp
                WHERE (cp.path = :PATH OR cp.path LIKE :PATH_CHILD)
                AND cp.cms_tree_id = :TREE_ID;";
        yii::$app->db->createCommand($sql, $sqlParams)->execute();
    }
}