<?php

namespace fl\cms\entities\dynamic_properties;

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
            $this->response['result'] = $this->_result;
        } else {
            $this->exceptionHandling('4', ['Test']);
        }
        
    }
    
    /**
     * @param int $id
     * @param int $user_id
     * @return array
     */
    private function setQueryParams(int $id, int $user_id): array
    {
        $data = [
            'user_id' => (int)$user_id,
            'action' => 'property_delete',
            'params' => 
            [
                'id' => (int)$id
            ]
        ];
        return $data;
    }
}