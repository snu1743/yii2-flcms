<?php

namespace fl\cms\entities\dynamic_properties;

use Yii;
use fl\cms\entities\base\BaseFlRecord;

class Create extends BaseFlRecord
{
    private const ACTION_NAME = 'create';
    
    public $settings = null;


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
        $queryParams = $this->setQueryParams($this->entity_class_id, $this->name, $this->description, $this->type, $this->settings);
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] = 'ok') {
            $this->_properties[] = $this->_result;
        } else {
            $this->exceptionHandling('1', ['Test']);
        }
        
    }
    
    /**
     * @param int $user_id
     * @param int $entity_class_id
     * @param string $name
     * @param string $description
     * @param int $type
     * @param string|null $settings
     * @return array
     */
    private function setQueryParams(int $entity_class_id, string $name, string $description, int $type, ?string $settings): array
    {
        $data = [
            'action' => 'property_create',
            'params' => 
                [
                    'class_id' => (int) $entity_class_id,
                    'name'=> $name,
                    'description' => $description,
                    'type'=> (int)$type,
                    'settings' => $settings  
                ]
        ];
        return $data;
    }
}