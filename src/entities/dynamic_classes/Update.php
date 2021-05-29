<?php

namespace fl\cms\entities\dynamic_classes;

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
        $queryParams = $this->setQueryParams($this->id, $this->user_id, $this->owner_id, $this->name, $this->description);
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] = 'ok') {
            $this->_properties[] = $this->_result;
        } else {
            $this->exceptionHandling('2', [json_encode($this->_result)]);
        }
    }
    
    /**
     * setQueryParams
     * @param int $id
     * @param int $user_id
     * @param int $owner_id
     * @param string $name
     * @param string $description
     * @return array
     */
    private function setQueryParams(int $id, int $user_id, int $owner_id, string $name, string $description): array
    {
        $data = [
            'action' => 'class_update',
            'params' => 
            [
                'id' => (int)$id,
                'owner_id' => (int)$owner_id,
                'name' => $name,
                'description' => $description
            ]
        ];
        return $data;
    }
}