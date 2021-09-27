<?php


namespace fl\cms\apps\items\pages\tree;

use yii;
use fl\cms\helpers\url\UrlBase;
use fl\cms\repositories\page\CmsСhildPages;
use fl\cms\helpers\user\Session;

class App extends \fl\cms\apps\base\AppMain
{
    private $templateItem = '<li class="nav-item">
                                <a href="{{htef}}" class="nav-link">
                                    <i class="fas fa-circle nav-icon"></i>
                                    <p>{{name}}</p>
                                </a>
                            </li>';

    public function exec(): string
    {
        $url = UrlBase::getCurrentPath();
        $childs = $this->getChildPages();
        return $this->createTree($childs);
    }

    /**
     * @param array $childs
     * @return string
     */
    private function createTree(array $childs)
    {
        $name = '';
        $path = '';
        $items = '';
        foreach ($childs as $item) {
            $nameArr = explode('/',  $item['cms_page']['name']);
            $name = array_pop($nameArr);
            $path = UrlBase::getHome() . '/' . $item['cms_page']['path'];
            $itemsTmp = str_replace('{{htef}}', $path, $this->templateItem);
            $itemsTmp = str_replace('{{name}}', $name, $itemsTmp);
            $items .= $itemsTmp;
        }
        return $items;
    }

    private function getChildPages()
    {
        $params['cms_page_id'] = $this->pageData['cms_page']['id'];
        $params['cms_project_id'] = Session::getProgectId();
        $params['cms_main_tree_id'] = Session::getMainTreeId();
        $params['user_id'] = Session::getUserId();
        return CmsСhildPages::get($params);
    }

}