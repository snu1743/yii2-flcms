<?php


namespace fl\cms\helpers\project;

use fl\cms\repositories\CmsAccess;
use yii;
use fl\cms\helpers\user\Session;
use fl\cms\repositories\page\Get;
use fl\cms\helpers\actions\ActionsConstants;
use fl\cms\helpers\cms\CmsConstants;
use fl\cms\helpers\project\ProjectConstants;
use fl\cms\repositories\project\CmsProjectRepository;
use fl\cms\repositories\page\MainRepositories as PageRepositories;
use fl\cms\repositories\group\CmsGroupRepository;
use fl\cms\repositories\tree\CmsTreeRepository;
use fl\cms\repositories\domain\CmsProjectDomainRepository;
use fl\cms\repositories\user\CmsUserRepository;
use fl\cms\helpers\tree\TreeConstants;
use fl\cms\external\Api;

class Create extends base\Main
{
    /**
     * Процесс создания страницы
     * @throws \Throwable
     */
    public function process(): void
    {
        $this->params['cms_project_status_id'] = ProjectConstants::PROJECT_STATUS_ENABLED;
        $this->params['cms_project_tree_bind_status_id'] = TreeConstants::PROJECT_TREE_BIND_ENABLED_STATUS;
        $this->params['user_id'] = Session::getUserId();

        $transaction = yii::$app->db->beginTransaction();
        try {
            $this->params['cms_project_params'] = CmsProjectRepository::create($this->params);
            $this->params['cms_tree_params'] = CmsTreeRepository::create($this->params);
            $this->params['cms_project_tree_bind_status_params'] = CmsTreeRepository::setCmsProjectTreeBind($this->params);
            $this->params['cms_project_domain_params_primary'] = CmsProjectDomainRepository::create($this->params, $this->params['project_domain_primary']);
            $this->params['cms_project_domain_params_secondary'] = CmsProjectDomainRepository::create($this->params, $this->params['project_domain_secondary']);

            $this->params['group_name'] = CmsGroupRepository::PROJECT_USERS_GROUP;
            $this->params['group_title'] = CmsGroupRepository::PROJECT_USERS_GROUP;
            $this->params['group_description'] = CmsGroupRepository::PROJECT_USER_GROUP_DESCRIPTION;
            $this->params['cms_user_group_params'] = CmsGroupRepository::create($this->params);
            $this->params['cms_project_group_bind_params'] = CmsGroupRepository::setCmsProjectGroupBind($this->params, $this->params['cms_user_group_params']);
            $this->params['cms_user_group_bind_params'] = CmsGroupRepository::setCmsGroupUserBind($this->params, $this->params['cms_user_group_params']);

            $this->params['group_name'] = CmsGroupRepository::PROJECT_ADMINS_GROUP;
            $this->params['group_title'] = CmsGroupRepository::PROJECT_ADMINS_GROUP;
            $this->params['group_description'] = CmsGroupRepository::PROJECT_ADMIN_GROUP_DESCRIPTION;
            $this->params['cms_admin_group_params'] = CmsGroupRepository::create($this->params);
            $this->params['cms_project_admin_group_bind_params'] = CmsGroupRepository::setCmsProjectGroupBind($this->params, $this->params['cms_admin_group_params']);
            $this->params['cms_group_admin_bind_params'] = CmsGroupRepository::setCmsGroupUserBind($this->params, $this->params['cms_admin_group_params']);

            $pageHome = $this->createProjectPageHome();
            $this->createProjectPageHello($pageHome);

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        /** TODO - Прервал работы до появления аккаунта реселлера. */
//        $this->setDomainAlias($this->params['alias_primary'], $this->params['main_domain'], $this->params['provider_prod']);
//        exit();
    }

    private function createProjectPageHome(): array
    {
        $params['name'] = $this->params['cms_project_params']['acronym'];
        $params['title'] = $this->params['cms_project_params']['acronym'];
        $params['path'] = '';
        $params['parent_id'] = 0;
        $params['owner_id']  = $this->params['user_id'];
        $params['cms_tree_id'] = $this->params['cms_tree_params']['id'];
        $params['level']= 0;
        $params['is_active'] = 1;

        $page = PageRepositories::create($params);

        $rules = [
            [$page['id'], CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_CREATE,  (int)$this->params['cms_admin_group_params']['id'], 'null'],
            [$page['id'], CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_VIEW,  0, 'null']
        ];
        CmsAccess::setRules($rules);
        return $page;
    }

    private function createProjectPageHello(array $pageHome)
    {
        $params['name'] = 'hello';
        $params['title'] = 'hello';
        $params['path'] = 'hello';
        $params['parent_id'] = $pageHome['id'];
        $params['owner_id']  = $this->params['user_id'];
        $params['cms_tree_id'] = $this->params['cms_tree_params']['id'];
        $params['level']= 0;
        $params['is_active'] = 1;

        $page = PageRepositories::create($params);

        $rules = [
            [$page['id'], CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_CREATE,  $this->params['cms_admin_group_params']['id'], 'null'],
            [$page['id'], CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_VIEW,  0, 'null'],
            [$page['id'], CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_UPDATE,  $this->params['cms_admin_group_params']['id'], 'null'],
            [$page['id'], CmsConstants::OBJECT_TYPE_PAGE, ActionsConstants::ACTION_PAGE_DELETE,  $this->params['cms_admin_group_params']['id'], 'null'],
        ];
        CmsAccess::setRules($rules);
    }

    private function setDomainAlias(string $alias, string $domain, string $provider)
    {
        Api::exec([
            'provider' => $provider,
            'action' => 'domains_add_alias',
            'domain' => $domain,
            'alias' => $alias
        ]);
    }

    private function setDomainCname()
    {
        Api::exec([
            [
                'provider' => 'reg.ru',
                'action' => 'domains_add_cname',
                'domain' => 'freelemur.com'
            ]
        ]);
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [];
    }
}