<?php

namespace fl\cms\entities\admin_user;

use Yii;
use fl\cms\entities\base\BaseFlRecord;

class Delete extends BaseFlRecord
{
    private const ACTION_NAME = 'delete';
    
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
        $this->delete();
        if($this->hasErrors()) {
            $this->response['errors'] = $this->getErrors();
            $this->response['errors']['status'] = true;
        } else {
            $this->response['errors']['status'] = false;
        }
    }
    
    private function delete()
    {
        
        $this->response['result']['status'] = true;
    }
}