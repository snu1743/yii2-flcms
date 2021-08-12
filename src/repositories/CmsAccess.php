<?php


namespace fl\cms\repositories;

use yii;
use yii\base\Exception;
use fl\cms\helpers\page\PageConstants;
use fl\cms\helpers\cms\CmsConstants;

class CmsAccess
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
                isset($rule[3]) && is_numeric($rule[3]) &&
                isset($rule[4]) && is_numeric($rule[4]) &&
                isset($rule[5]) && is_numeric($rule[5])
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
        $sql = "INSERT INTO cms_access_rule (cms_access_object_id, cms_access_object_type_id, cms_object_action_id, role_type_id, role_id, cms_project_id)
            VALUES $itemRule;";
        return yii::$app->db->createCommand($sql, $sqlParams)->execute();
    }

    /**
     * Запрос для проверки прав на действие CMS
     * @param array $params
     * @return array
     * @throws yii\db\Exception
     */
    public static function checkAccess(array $params): array
    {
        $sqlParams = [
            ':OBJECT_ID' => (int)$params['cms_access_object_id'],
            ':OBJECT_TYPE_ID' => (int)$params['cms_access_object_type_id'],
            ':PROJECT_ID' => (int)$params['cms_project_id'],
            ':ACTION_ID' => (int)$params['cms_object_action_id'],
            ':ROLE_TYPE_ID_USER' => CmsConstants::ROLE_TYPE_ID_USER,
            ':ROLE_TYPE_ID_GROUP' => CmsConstants::ROLE_TYPE_ID_GROUP,
            ':USER_ID' => (int)$params['user_id']
        ];
        $groupIdsList = implode(",", $params['group_ids']);
        $sql =
            "SELECT
                (
                    count(*) +
                    (
                        SELECT
                            count(*)
                        FROM cms_access_rule car
                        WHERE car.cms_access_object_id = :OBJECT_ID
                        AND car.cms_access_object_type_id = :OBJECT_TYPE_ID
                        AND car.cms_object_action_id = :ACTION_ID
                        AND car.role_type_id = :ROLE_TYPE_ID_GROUP
                        AND car.role_id IN (0$groupIdsList)
                        AND car.cms_project_id IN (0, :PROJECT_ID)
                    )
                ) as result
                FROM cms_access_rule car
                WHERE car.cms_access_object_id = :OBJECT_ID
                AND car.cms_access_object_type_id = :OBJECT_TYPE_ID
                AND car.cms_object_action_id = :ACTION_ID
                AND car.role_type_id = :ROLE_TYPE_ID_USER
                AND car.role_id = :USER_ID
                AND car.cms_project_id IN (0, :PROJECT_ID)";
        return yii::$app->db->createCommand($sql, $sqlParams)->queryOne();
    }
}