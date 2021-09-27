<?php


namespace fl\cms\helpers\page;

use yii;
use fl\cms\helpers\user\Session;
use fl\cms\repositories\page\Get;
use fl\cms\helpers\actions\ActionsConstants;
use fl\cms\helpers\cms\CmsConstants;
use fl\cms\helpers\page\PageConstants;
use fl\cms\repositories\page\MainRepositories as Page;
use fl\cms\repositories\page\Content;
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
        $this->prepareParamsCreate();
        if (!Access::check($this->params)) {
            throw new yii\web\ForbiddenHttpException('Access denied');
        };
        $transaction = yii::$app->db->beginTransaction();
        try {
            $result['page_data'] = $this->createCmsPage();
            $this->createCmsPageAccess($result['page_data']['id'], $this->params['parent_id']);
            $result['page_content_data'] = $this->setCmsPageContent();
            $this->bindCmsPageContent($result['page_data']['id'], $result['page_content_data']['id']);
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
        return Page::create($this->params);
    }

    /**
     * @return array
     * @throws yii\db\Exception
     */
    private function setCmsPageContent(): array
    {
        return Content::insert($this->params);
    }

    /**
     * @param int $id
     * @param int $contentId
     * @return array
     */
    private function bindCmsPageContent(int $id, int $contentId): array
    {
        $this->params['cms_page_layout_content_bind']['cms_page_id'] = $id;
        $this->params['cms_page_layout_content_bind']['cms_page_content_id'] = $contentId;
        $this->params['cms_page_layout_content_bind']['version'] = 0;
        return Content::bind($this->params['cms_page_layout_content_bind']);
    }

    /**
     * Присвоенеи прав доступа для владельца страницы
     * @param int $id
     * @throws \Exception
     */
    private function createCmsPageAccess(int $id, int $projectId): void
    {
        $rules = [
            [$id, CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_CREATE,  'null', $this->params['owner_id']],
            [$id, CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_VIEW,  'null', $this->params['owner_id']],
            [$id, CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_UPDATE,  'null', $this->params['owner_id']],
            [$id, CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_DELETE,  'null', $this->params['owner_id']],
        ];
        CmsAccess::setRules($rules);
    }
}