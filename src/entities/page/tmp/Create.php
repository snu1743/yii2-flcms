<?php

namespace fl\cms\entities\page;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use luya\cms\admin\Module;
use luya\cms\models\Nav;

class Create extends BaseFlRecord
{
    private const ACTION_NAME = 'create';
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules(self::ACTION_NAME);
    }
    
    public function getActionName() {
        return self::ACTION_NAME;
    }
    
    public function initModel()
    {
        $this->createPage();
    }
    
    private function createPage()
    {
        Module::setAuthorUserId(1);
        $this->params['use_draft'] = $this->params['use_draft'] ?? null;
        $this->params['parent_id'] = $this->params['parent_id'];
        $this->params['nav_container_id'] = $this->params['nav_container_id'] ?? 1;
        $this->params['lang_id'] = $this->params['lang_id'] ?? 1;
        $this->params['layout_id'] = $this->params['layout_id'] ?? 2;
        $this->params['is_draft'] = $this->params['is_draft'] ?? 0;
        $this->params['from_draft_id'] =  $this->params['from_draft_id'] ?? null;
        $this->params['title'] = $this->params['title'] ?? null;
        $this->params['alias'] = $this->params['alias'];
        $this->params['description'] = $this->params['description'] ?? null;
        
        $this->menuFlush();
        $model = new Nav();
        
        if (!empty($this->params['parent_id'])) {
            $this->params['nav_container_id'] = Nav::findOne($this->params['parent_id'])->nav_container_id;
        }
        
        if (!empty($this->params['use_draft'])) {
            $create = $model->createPageFromDraft($this->params['parent_id'], $this->params['nav_container_id'], $this->params['lang_id'], $this->params['title'], $this->params['alias'], $this->params['description'], $this->params['from_draft_id'], $this->params['is_draft']);
        } else {
            
            $create = $model->createPage($this->params['parent_id'], $this->params['nav_container_id'], $this->params['lang_id'], $this->params['title'], $this->params['alias'], $this->params['layout_id'], $this->params['description'], $this->params['is_draft']);
            $model->is_offline = 0;
            $model->is_hidden = 0;
            $model->save(false);
//            echo $create;
        }
        
        if (is_array($create)) {
            $this->response['errors']['data'] = json_encode($create);
            $this->response['errors']['msg'] = 'Page creation error';
            $this->response['errors']['status'] = 'fail';
        } elseif (is_numeric($create)){
            $this->response['errors']['status'] = false;
            $this->response['params'] = $create;
        } else {
            $this->response['errors']['status'] = 'fail';
            $this->response['errors']['msg'] = 'Page creation error';
        }
        return $this;
    }
    
    /**
     * Flush the menu data if component exits.
     *
     * @since 1.0.6
     */
    protected function menuFlush()
    {
        if (Yii::$app->get('menu', false)) {
            Yii::$app->menu->flushCache();
        }
    }
}