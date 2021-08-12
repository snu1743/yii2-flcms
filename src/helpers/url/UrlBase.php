<?php


namespace fl\cms\helpers\url;

class UrlBase
{
    public static function getCurrentPath()
    {
        $uriParts = explode('?', urldecode($_SERVER['REQUEST_URI']), 2);
        return trim($uriParts[0], '/');
    }

    public static function getHome()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
    }

    /**
     * @return string
     */
    public static function getProjectDomainName(): string
    {
        return $_SERVER['SERVER_NAME'];
    }
}