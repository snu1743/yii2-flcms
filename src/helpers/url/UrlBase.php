<?php


namespace fl\cms\helpers\url;

class UrlBase
{
    public static function getCurrentPath()
    {
        $uriParts = explode('?', $_SERVER['REQUEST_URI'], 2);
        return trim($uriParts[0], '/');
    }

    public static function getHome()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
    }
}