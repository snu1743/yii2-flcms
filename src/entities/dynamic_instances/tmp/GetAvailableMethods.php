<?php

namespace fl\cms\entities\dynamic_instances;

use Yii;
use fl\cms\entities\base\BaseFlRecord;


class GetAvailableMethods extends BaseFlRecord
{
    
    private const ACTION_NAME = 'get_available_methods';
    
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
//        if(!isset($this->user_id)) {
//            $session = Yii::$app->session;
//            $this->user_id = $session['person']['id'];
//        }
        $queryParams = $this->setQueryParams($this->instance_id);
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] === 'ok') {
            $this->_properties = $this->_result['params']['rows'];
        } else {
            $this->exceptionHandling('7', ['Test']);
        }
        
    }
    
    private function setQueryParams( int $instance_id): array
    {
        $data = [
            "action" => "get_available_methods",
            "params" => [
                "instance_id" => $instance_id
            ]
        ];
        return $data;
    }
}