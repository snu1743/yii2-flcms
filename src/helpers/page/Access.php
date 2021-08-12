<?php


namespace fl\cms\helpers\page;

use fl\cms\repositories\CmsAccess;

class Access
{
    /**
     * Проверка разрешение на дествие CMS
     * @param array $params
     * @return bool
     */
    public static function check(array $params): bool
    {
        if (!isset($params['cms_page_id']) || !is_numeric($params['cms_page_id'])) {
            return false;
        }
        if (!isset($params['cms_project_id']) || !is_numeric($params['cms_project_id'])) {
            return false;
        }
        if (!isset($params['cms_object_action_id']) || !is_numeric($params['cms_object_action_id'])) {
            return false;
        }
        if (!isset($params['user_id']) || !is_numeric($params['user_id'])) {
            return false;
        }
        if (isset($params['group_ids']) && !is_array($params['group_ids'])) {
            return false;
        }
        $cmsAccess = CmsAccess::checkAccess($params);
        if ((int)$cmsAccess['result'] !== 0) {
            return true;
        }
        return false;
    }
}