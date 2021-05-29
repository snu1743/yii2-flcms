<?php


namespace fl\cms\helpers\page;

use fl\cms\repositories\CmsPageAccess;

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
        if (!isset($params['cms_page_action_id']) || !is_numeric($params['cms_page_action_id'])) {
            return false;
        }
        if (!isset($params['user_id']) || !is_numeric($params['user_id'])) {
            return false;
        }
        if (isset($params['group_ids']) && !is_array($params['group_ids'])) {
            return false;
        }
        $result = CmsPageAccess::checkPageAccess($params);
        if ((int)$result['user'] !== 0) {
            return true;
        }
        if ((int)$result['group'] !== 0) {
            return true;
        }
        return false;
    }
}