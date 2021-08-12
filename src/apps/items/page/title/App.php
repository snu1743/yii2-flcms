<?php


namespace fl\cms\apps\items\page\title;

use yii;
use fl\cms\helpers\url\UrlBase;
use fl\cms\repositories\CmsСhildPages;
use fl\cms\helpers\user\Session;

class App extends \fl\cms\apps\base\AppMain
{
    public function exec(): string
    {
        return "<title>{$this->pageData['cms_page']['title']}</title>";
    }

}