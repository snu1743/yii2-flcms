<?php

namespace fl\cms\entities\dynamic_instances;

use Yii;
use fl\cms\entities\base\BaseFlRecord;


class GetConstructors extends BaseFlRecord
{
    
    private const ACTION_NAME = 'get_constructors';
    
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
        $queryParams = $this->setQueryParams($this->user_id);
        $this->_result = $this->sendQuery($queryParams);
        print_r($this->_result); exit;
        if(isset($this->_result['status']) && $this->_result['status'] === 'ok') {
            $this->_properties = $this->_result['params']['rows'];
        } else {
            $this->exceptionHandling('8', [json_encode($this->_result)]);
        }
        
    }
    
    private function setQueryParams( int $user_id): array
    {
        $data = ["user_id" => $user_id,
                "action" => "get_constructors",
                "params" => [
                "entity_id" => 3,
                "class_id" => 15
            ]
        ];
//        print_r($data); exit;
        return $data;
    }
}