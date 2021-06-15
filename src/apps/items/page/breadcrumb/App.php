<?php


namespace fl\cms\apps\items\page\breadcrumb;

use yii\helpers\BaseUrl;

class App extends \fl\cms\apps\base\AppMain
{
    private $template = '<ol class="breadcrumb float-sm-right">{{items}}</ol>';
    private $templateItem = '<li class="breadcrumb-item"><a href="{{htef}}">{{title}}</a></li>';

    public function exec(): string
    {
        $pathItems = explode('/', $this->pageData['cms_page']['path']);
        return $this->createBreadcrumb($pathItems);
    }

    private function createBreadcrumb(array $pathItems): string
    {
        $title = '';
        $path = '';
        $items = '';
        foreach ($pathItems as $item) {
            $title = $item;
            $path .= "/$item";
            $itemsTmp = str_replace('{{htef}}', $path, $this->templateItem);
            $itemsTmp = str_replace('{{title}}', $title, $itemsTmp);
            $items .= $itemsTmp;
        }
        return str_replace('{{items}}', $items, $this->template);
    }
}