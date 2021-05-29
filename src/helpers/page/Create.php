<?php


namespace fl\cms\helpers\page;

use yii;
use fl\cms\helpers\page\base\PageConstants;
use fl\cms\repositories\CmsCreate;
use fl\cms\repositories\CmsUpdate;
use fl\cms\repositories\CmsPageAccess;

class Create extends base\Main
{
    /**
     * Процесс создания страницы
     * @throws \Throwable
     */
    public function process(): void
    {
        $this->prepareParams();
        if (!Access::check($this->params)) {
            throw new \Exception('Access denied');
        };
        $transaction = yii::$app->db->beginTransaction();
        try {
            $result = $this->createCmsPage();
            $this->createCmsPageAccess($result['id']);
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
     * Добавление не обходимых параметров
     */
    private function prepareParams()
    {
        $this->params['cms_page_action_id'] = PageConstants::ACTION_CREATE_ID;
        $this->params['cms_page_id'] = $this->params['parent_id'];
        $this->params['owner_id'] = (isset($this->params['owner_id'])) ? $this->params['owner_id'] : $this->params['user_id'];
        $this->params['level'] = $this->setLevel($this->params['path']);
        $this->params['is_active'] = (isset($this->params['is_active'])) ? (int)$this->params['is_active'] : 1;
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
    private function createCmsPageAccess(int $id): void
    {
        $rules = [
            [$id, PageConstants::ACTION_CREATE_ID, CmsPageAccess::ROLE_TYPE_ID_USER, $this->params['owner_id']],
            [$id, PageConstants::ACTION_DELETE_ID, CmsPageAccess::ROLE_TYPE_ID_USER, $this->params['owner_id']],
            [$id, PageConstants::ACTION_UPDATE_ID, CmsPageAccess::ROLE_TYPE_ID_USER, $this->params['owner_id']],
            [$id, PageConstants::ACTION_VIEW_ID, CmsPageAccess::ROLE_TYPE_ID_USER, $this->params['owner_id']],
        ];
        CmsPageAccess::setRules($rules);
    }
}