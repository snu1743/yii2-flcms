<?php

namespace fl\cms\entities\dynamic_properties;

use Yii;
use fl\cms\entities\base\BaseFlRecord;

class Update extends BaseFlRecord
{
    private const ACTION_NAME = 'update';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules(self::ACTION_NAME);
    }
    
    public function initModel()
    {
        $queryParams = $this->setQueryParams($this->id, $this->entity_class_id, $this->name, $this->description, $this->type);
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] = 'ok') {
            $this->_properties[] = $this->_result;
        } else {
            $this->exceptionHandling('2', [json_encode($this->_result)]);
        }
    }
    
    private function setQueryParams(int $id, int $entity_class_id, string $name, string $description, int $type): array
    {
        $data = [
            'action' => 'property_update',
            'params' => 
            [
                'id' => (int)$id,
                'class_id' => (int)$entity_class_id,
                'name' => (string)$name,
                'description' => (string)$description,
                'type' => (int)$type,
                'setting' => null
            ]
        ];
        return $data;
    }
}