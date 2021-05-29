<?php


namespace fl\cms\entities\page;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use luya\cms\admin\Module;
use fl\cms\entities\page\base\Nav;
//luya\cms\models\Nav;

class Create extends BaseFlRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules();
    }

    /**
     * @inheritdoc
     */
    public function initModel(): void
    {
        Module::setAuthorUserId(1);
        $params['use_draft'] = ($this->hasAttribute('use_draft')) ? $this->use_draft : null;
        $params['parent_id'] = ($this->hasAttribute('parent_id') && is_numeric($this->parent_id)) ? $this->parent_id : 0;
        $params['nav_container_id'] = ($this->hasAttribute('nav_container_id') && is_numeric($this->nav_container_id)) ? $this->nav_container_id : 1;
        $params['lang_id'] = ($this->hasAttribute('nav_container_id') && is_numeric($this->lang_id)) ? $this->lang_id : 1;
        $params['layout_id'] = ($this->hasAttribute('layout_id') && is_numeric($this->layout_id)) ? $this->layout_id : 2;
        $params['is_draft'] = ($this->hasAttribute('is_draft') && is_numeric($this->is_draft)) ? $this->is_draft : 0;
        $params['from_draft_id'] =  ($this->hasAttribute('from_draft_id') && is_numeric($this->from_draft_id)) ? $this->from_draft_id : null;
        $params['title'] = $this->title;
        $params['alias'] = $this->alias;
        $params['description'] = ($this->hasAttribute('description') && is_string($this->description)) ? $this->description : null;
        $this->menuFlush();
        $model = new Nav();
        if (!empty($params['parent_id'])) {
            $params['nav_container_id'] = Nav::findOne($params['parent_id'])->nav_container_id;
        }
        if (!empty($params['use_draft'])) {
            $create = $model->createPageFromDraft($params['parent_id'], $params['nav_container_id'], $params['lang_id'], $params['title'], $params['alias'], $params['description'], $params['from_draft_id'], $params['is_draft']);
        } else {
            $create = $model->createPage($params['parent_id'], $params['nav_container_id'], $params['lang_id'], $params['title'], $params['alias'], $params['layout_id'], $params['description'], $params['is_draft']);
            $model->is_offline = 0;
            $model->is_hidden = 0;
            $model->save(false);
        }
        if (is_array($create)) {
            $this->_properties[] = $create;
        } elseif (is_numeric($create)){
            $this->_properties[] = ['id' => $create];
        } else {
            $this->_properties[] = ['id' => null];
        }
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