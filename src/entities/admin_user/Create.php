<?php

namespace fl\cms\entities\admin_user;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use fl\cms\models\AdminUser;

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
        $this->create();
        if($this->hasErrors()) {
            $this->response['errors'] = $this->getErrors();
            $this->response['errors']['status'] = true;
        } else {
            $this->response['errors']['status'] = false;
        }
    }
    
    private function create()
    {
        
//        $adminUser = new AdminUser();
//        $adminUser->save();
    }
}