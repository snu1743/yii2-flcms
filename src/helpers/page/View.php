<?php


namespace fl\cms\helpers\page;

use yii;
use fl\cms\repositories\CmsView;
use fl\cms\repositories\page\Get;
use fl\cms\helpers\actions\ActionsConstants;
use fl\cms\helpers\cms\CmsConstants;

class View extends base\Main
{
    /**
     * Получение данных страницы
     * @throws \Throwable
     */
    public function process(): void
    {
        $this->params['cms_object_action_id'] = ActionsConstants::ACTION_PAGE_VIEW;
        $this->prepareParams();
        if (!Access::check($this->params)) {
            throw new yii\web\ForbiddenHttpException('Access denied');
        };
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