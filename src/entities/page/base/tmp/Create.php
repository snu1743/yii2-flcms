<?php

namespace fl\cms\entities\page\base;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use luya\cms\admin\Module;
use luya\cms\models\Nav;

/**
 * Description of Create
 *
 * @author snu5998
 */
class Create {
    
    public function execute($params)
    {
        Module::setAuthorUserId(1);
        $params['use_draft'] = $params['use_draft'] ?? null;
        $params['parent_id'] = $params['parent_id'];
        $params['nav_container_id'] = $params['nav_container_id'] ?? 1;
        $params['lang_id'] = $params['lang_id'] ?? 1;
        $params['layout_id'] = $params['layout_id'] ?? 2;
        $params['is_draft'] = $params['is_draft'] ?? 0;
        $params['from_draft_id'] =  $params['from_draft_id'] ?? null;
        $params['title'] = $params['title'] ?? null;
        $params['alias'] = $params['alias'];
        $params['description'] = $params['description'] ?? null;
        
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
//            echo $create;
        }
        
        if (is_array($create)) {
            $response['errors']['data'] = json_encode($create);
            $response['errors']['msg'] = 'Page creation error';
            $response['errors']['status'] = 'fail';
        } elseif (is_numeric($create)){
            $response['errors']['status'] = false;
            $response['params'] = $create;
        } else {
            $response['errors']['status'] = 'fail';
            $response['errors']['msg'] = 'Page creation error';
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
