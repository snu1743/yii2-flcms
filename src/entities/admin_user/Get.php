<?php

namespace fl\cms\entities\admin_user;

use Yii;
use fl\cms\entities\base\BaseFlRecord;

class Get extends BaseFlRecord
{
    private const ACTION_NAME = 'get';
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules(self::ACTION_NAME);
    }
    
    public function initModel()
    {
        $this->get();
        if($this->hasErrors()) {
            $this->response['errors'] = $this->getErrors();
            $this->response['errors']['status'] = true;
        } else {
            $this->response['errors']['status'] = false;
        }
    }
    
    private function get()
    {
        $properties = (new \yii\db\Query())
            ->select(['*'])
            ->from('admin_user')
            ->limit(10)
            ->all();
        $this->_properties = $properties;
    }
}