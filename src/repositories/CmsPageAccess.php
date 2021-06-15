<?php


namespace fl\cms\repositories;

use yii;
use yii\base\Exception;
use fl\cms\helpers\page\base\PageConstants;

class CmsPageAccess
{
    /**
     * Запрос на создание прав
     * @param array $rules
     * @throws yii\db\Exception
     */
    public function setRules(array $rules)
    {
        $itemRule = '';
        foreach ($rules as $key => $rule) {
            if (
                isset($rule[0]) && is_numeric($rule[0]) &&
                isset($rule[1]) && is_numeric($rule[1]) &&
                isset($rule[2]) && is_numeric($rule[2]) &&
                isset($rule[3]) && is_numeric($rule[3])
            ) {
                $rule = implode(',', $rule);
                $itemRule .= "($rule)";
                unset($rules[$key]);
                if (count($rules) !== 0) {
                    $itemRule .= ',';
                }
            } else {
                throw new \Exception('Invalid set of rules');
            }
        }
        $sqlParams = [];
        $sql = "INSERT INTO cms_page_access (cms_page_id, cms_page_action_id, role_type_id, role_id)
            VALUES $itemRule;";
        return yii::$app->db->createCommand($sql, $sqlParams)->execute();
    }

    /**
     * Запрос для проверки прав на действие CMS
     * @param array $params
     * @return mixed
     */
    public static function checkPageAccess(array $params)
    {
        $sqlParams = [
            ':CMS_PAGE_ID' => (int)$params['cms_page_id'],
            ':CMS_PAGE_ACTION_ID' => (int)$params['cms_page_action_id'],
            ':ROLE_TYPE_USER' => PageConstants::ROLE_TYPE_ID_USER
        ];
        $roleIds = '0, ' . (int)$params['user_id'];
        $groupStatus = false;
        if (isset($params['group_ids']) && is_array($params['group_ids']) && count($params['group_ids']) > 0) {
            $groupStatus = true;
            foreach ($params['group_ids'] as $groupId) {
                if (!is_numeric($groupId)) {
                    $groupStatus = false;
                }
            }
        }
        $sqlGroup = '';
        if ($groupStatus) {
            $sqlParams[':ROLE_TYPE_GROUP'] = PageConstants::ROLE_TYPE_ID_GROUP;
            $groupIdsList = implode(",", $params['group_ids']);;
            $sqlGroup = ",
                    (
                        SELECT
                            count(*)
                        FROM cms_page_access cpa
                        WHERE cpa.cms_page_id = :CMS_PAGE_ID
                        AND cpa.cms_page_action_id = :CMS_PAGE_ACTION_ID
                        AND cpa.role_type_id = :ROLE_TYPE_GROUP
                        AND cpa.role_id IN ($groupIdsList)
                    ) as group";
        }
        $sql = "SELECT
                    count(*) as user$sqlGroup
                FROM cms_page_access cpa
                WHERE cpa.cms_page_id = :CMS_PAGE_ID
                AND cpa.cms_page_action_id = :CMS_PAGE_ACTION_ID
                AND cpa.role_type_id = :ROLE_TYPE_USER
                AND cpa.role_id IN ($roleIds)";
        return yii::$app->db->createCommand($sql, $sqlParams)->queryOne();
    }
}