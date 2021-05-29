<?php


namespace fl\cms\helpers\page;

use yii;
use fl\cms\repositories\CmsView;

class View extends base\Main
{
    /**
     * Получение данных страницы
     * @throws \Throwable
     */
    public function process(): void
    {
        $this->result = $this->getPageData();
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            [['path'], 'required'],
            ['path', 'string', 'min' => 1, 'max' => 2048]
        ];
    }

    private function getPageData()
    {
        return CmsView::getPageData($this->params);
    }
}