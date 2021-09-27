<?php


namespace fl\cms\helpers\page;

/**
 * Class PageConstants
 * @package fl\cms\helpers\page\base
 */
class PageConstants
{
    public const PAGE_STATUS_ACTIVE = 1;
    public const PAGE_ACTION_LIST = [
        'page_create' => 1,
        'page_view' => 2,
        'page_update' => 3,
        'page_delete' => 4,
    ];
}