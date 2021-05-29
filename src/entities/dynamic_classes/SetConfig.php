<?php

namespace fl\cms\entities\dynamic_classes;

use Yii;
use fl\cms\entities\base\BaseFlRecord;


class SetConfig extends BaseFlRecord
{
    
//    private const ACTION_NAME = 'get_constructors';
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules();
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
        $queryParams = $this->setQueryParams($this->user_id, $this->entity_class_id, $this->content);
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] === 'ok') {
            $this->response['result'] = $this->_result;
        } else {
            $this->exceptionHandling('8', [json_encode($this->_result)]);
        }
        
    }
    
    private function setQueryParams( int $user_id, int $entity_class_id, string $config): array
    {
        $data = ["user_id" => $user_id,
                "action" => "class_set_config",
                "params" => [
                "id" => $entity_class_id,
                "config" => $config
            ]
        ];
        return $data;
    }
}