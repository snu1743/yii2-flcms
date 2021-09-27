<?php


namespace fl\cms\apps\items\pages\breadcrumb;

use yii\helpers\BaseUrl;

class App extends \fl\cms\apps\base\AppMain
{
    private $template = '<ol class="breadcrumb float-sm-right">{{items}}</ol>';
    private $templateItem = '<li class="breadcrumb-item"><a href="{{href}}">{{name}}</a></li>';

    public function exec(): string
    {
        exit();
        $pathItems = explode('/', $this->pageData['cms_page']['path']);
        $nameItems = explode('/', $this->pageData['cms_page']['name']);
        return $this->createBreadcrumb($pathItems, $nameItems);
    }

    private function createBreadcrumb(array $pathItems, array $nameItems): string
    {
        $name = '';
        $path = '';
        $items = '';
        foreach ($pathItems as $key => $item) {
            $name = $nameItems[$key];
            $path .= "/$item";
            $itemsTmp = str_replace('{{href}}', $path, $this->templateItem);
            $itemsTmp = str_replace('{{name}}', $name, $itemsTmp);
            $items .= $itemsTmp;
        }
        return str_replace('{{items}}', $items, $this->template);
    }
}