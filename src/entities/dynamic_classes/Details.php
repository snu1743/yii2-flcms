<?php

namespace fl\cms\entities\dynamic_classes;

use Yii;
use fl\cms\entities\base\BaseFlRecord;

class Details extends BaseFlRecord
{
    private const ACTION_NAME = 'details';
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules(self::ACTION_NAME);
    }
    
    /**
     * @return void
     */
    public function initModel(): void
    {
        if(!isset($this->user_id)) {
            $session = Yii::$app->session;
            $this->user_id = $session['person']['id'];
        }
        $queryParams = $this->setQueryParams($this->id, $this->user_id);
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] = 'ok') {
            $this->_properties[] = $this->_result['params'];
        } else {
            $this->exceptionHandling('5', ['Test']);
        }
    }
    
    /**
     * @param int $id
     * @param int $user_id
     * @return array
     */
    private function setQueryParams( int $id, int $user_id): array
    {
        $data = [
            'user_id' => (int)$user_id,
            'action' => 'class_details',
            'params' => 
                [
                    'id' => (int) $id 
                ]
        ];
        return $data;
    }
}