<?php


namespace fl\cms\helpers\page;

use yii;
use fl\cms\helpers\user\Session;
use fl\cms\repositories\page\Get;
use fl\cms\helpers\actions\ActionsConstants;
use fl\cms\repositories\page\Main;
use fl\cms\repositories\CmsAccess;

class Delete extends base\Main
{
    /**
     * Процесс создания страницы
     * @throws \Throwable
     */
    public function process(): void
    {
        $this->params['cms_object_action_id'] = ActionsConstants::ACTION_PAGE_DELETE;
        $this->prepareParams();
        if (!Access::check($this->params)) {
            throw new yii\web\ForbiddenHttpException('Access denied');
        };
        $transaction = yii::$app->db->beginTransaction();
        try {
            $result['page_content_data'] = $this->deleteCmsPageContent();
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            [['path'], 'required'],
            ['path', 'string', 'min' => 1, 'max' => 250]
        ];
    }

    /**
     * @return array
     */
    private function deleteCmsPageContent()
    {
        return Main::delete($this->params);
    }
}