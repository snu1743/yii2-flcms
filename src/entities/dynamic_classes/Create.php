<?php

namespace fl\cms\entities\dynamic_classes;

use Yii;
use fl\cms\entities\base\BaseFlRecord;

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
    
    public function initModel()
    {
        if(!isset($this->owner_id)) {
            $session = Yii::$app->session;
            $this->owner_id = $session['person']['id'];
        }
        $queryParams = $this->setQueryParams($this->owner_id, $this->name, $this->description);
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] === 'ok') {
            $this->_properties[] = $this->_result['params'];
        } else {
            $this->exceptionHandling('1', [json_encode($this->_result)]);
        }
    }

    private function setQueryParams(int $owner_id, string $name, string $description): array
    {
        $data = [
            'action' => 'class_create',
            'params' =>
                [
                    'owner_id' => (int)$owner_id,
                    'name' => $name,
                    'description' => $description
                ]
        ];
        return $data;
    }
}