<?php


namespace fl\cms\helpers\page;

use yii;
use fl\cms\helpers\user\Session;
use fl\cms\repositories\page\Get;
use fl\cms\helpers\actions\ActionsConstants;
use fl\cms\helpers\cms\CmsConstants;
use fl\cms\helpers\page\PageConstants;
use fl\cms\repositories\CmsCreate;
use fl\cms\repositories\CmsUpdate;
use fl\cms\repositories\CmsAccess;

class Create extends base\Main
{
    /**
     * Процесс создания страницы
     * @throws \Throwable
     */
    public function process(): void
    {
        $this->params['cms_page_id'] = $this->params['parent_id'];
        $this->params['owner_id'] = Session::getUserId();
        $this->params['level'] = $this->setLevel($this->params['path']);
        $this->params['is_active'] = PageConstants::PAGE_STATUS_ACTIVE;
        $this->params['cms_object_action_id'] = ActionsConstants::ACTION_PAGE_CREATE;
        $this->prepareParams();
        if (!Access::check($this->params)) {
            throw new yii\web\ForbiddenHttpException('Access denied');
        };
        $transaction = yii::$app->db->beginTransaction();
        try {
            $result = $this->createCmsPage();
            $this->createCmsPageAccess($result['id'], $this->params['parent_id']);
            $this->setCmsPageContent($result['id']);
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
            [['path', 'parent_id', 'user_id', 'body'], 'required'],
            ['path', 'string', 'min' => 1, 'max' => 250],
            ['body', 'string', 'min' => 5]
        ];
    }

    /**
     * @param string $path
     * @return int
     */
    private function setLevel(string $path): int
    {
        return count(explode('/', $path));
    }

    /**
     * Создание страницы
     * @return array
     */
    private function createCmsPage(): array
    {
        return CmsCreate::page($this->params);
    }

    private function setCmsPageContent(int $id): array
    {
        return CmsUpdate::setBody($id, $this->params);
    }

    /**
     * Присвоенеи прав доступа для владельца страницы
     * @param int $id
     * @throws \Exception
     */
    private function createCmsPageAccess(int $id, int $projectId): void
    {
        $rules = [
            [$id, CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_CREATE, CmsConstants::ROLE_TYPE_ID_USER, $this->params['owner_id'], $projectId],
            [$id, CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_VIEW, CmsConstants::ROLE_TYPE_ID_USER, $this->params['owner_id'], $projectId],
            [$id, CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_UPDATE, CmsConstants::ROLE_TYPE_ID_USER, $this->params['owner_id'], $projectId],
            [$id, CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_DELETE, CmsConstants::ROLE_TYPE_ID_USER, $this->params['owner_id'], $projectId],
        ];
        CmsAccess::setRules($rules);
    }
}