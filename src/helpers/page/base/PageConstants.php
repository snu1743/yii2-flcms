<?php


namespace fl\cms\helpers\page\base;


class PageConstants
{
    public const ACTION_CREATE_ID = 1;
    public const ACTION_VIEW_ID = 2;
    public const ACTION_UPDATE_ID = 3;
    public const ACTION_DELETE_ID = 4;
    public const CHECK_ACCESS = [
        self::ACTION_CREATE_ID
    ];
}