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
            if($rule[3] === null){
                $rule[3] = 'null';
            }
            if($rule[4] === null){
                $rule[4] = 'null';
            }
            if (
                isset($rule[0]) && is_numeric($rule[0]) &&
                isset($rule[1]) && is_numeric($rule[1]) &&
                isset($rule[2]) && is_numeric($rule[2]) &&
                ($rule[3] === 'null' || is_integer($rule[3])) &&
                ($rule[4] === 'null' || is_integer($rule[4]))
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
        $sql = "INSERT INTO cms_access_rule (cms_access_object_id, cms_access_object_type_id, cms_object_action_id, cms_group_id, user_id)
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
            ':ACTION_ID' => (int)$params['cms_object_action_id'],
            ':USER_ID' => (int)$params['user_id']
        ];
        $groupIdsList = '';
        if(count($params['group_ids']) > 0){
            $groupIdsList = ',' . implode(",", $params['group_ids']);
        }
        $sql =
            "SELECT
                (
                    count(*)
                ) as result
                FROM cms_access_rule car
                WHERE car.cms_access_object_id = :OBJECT_ID
                AND car.cms_access_object_type_id = :OBJECT_TYPE_ID
                AND car.cms_object_action_id = :ACTION_ID
                AND (car.user_id = :USER_ID OR car.cms_group_id IN (0$groupIdsList))
                LIMIT 1";
        $return = yii::$app->db->createCommand($sql, $sqlParams)->queryOne();
        return $return;
    }
}