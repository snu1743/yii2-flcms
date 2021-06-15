<?php


namespace fl\cms\apps\items\page\tree;

use yii;
use fl\cms\helpers\url\UrlBase;
use fl\cms\repositories\CmsСhildPages;

class App extends \fl\cms\apps\base\AppMain
{
    private $templateItem = '<li class="nav-item">
                            <a href="{{htef}}" class="nav-link">
                                <i class="fas fa-circle nav-icon"></i>
                                <p>{{title}}</p>
                            </a>
                        </li>';

    public function exec(): string
    {
        $url = UrlBase::getCurrentPath();
        $childs = $this->getChildPages();
        return $this->createTree($childs);
    }
    private function createTree(array $childs)
    {
        $title = '';
        $path = '';
        $items = '';
        foreach ($childs as $item) {
            $titleArr = explode('/',  $item['cms_page']['path']);
            $title = array_pop($titleArr);
            $path = UrlBase::getHome() . '/' . $item['cms_page']['path'];
            $itemsTmp = str_replace('{{htef}}', $path, $this->templateItem);
            $itemsTmp = str_replace('{{title}}', $title, $itemsTmp);
            $items .= $itemsTmp;
        }
        return $items;
    }

    private function getChildPages()
    {
        $params['cms_page_id'] = $this->pageData['cms_page']['id'];
        $params['cms_page_id'] = $this->pageData['cms_page']['id'];
        $params['user_id'] = 1;
        return CmsСhildPages::get($params);
    }

}