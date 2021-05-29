<?php


namespace fl\cms\entities\dynamic_classes;

use Yii;
use fl\cms\entities\base\BaseFlRecord;

/**
 * Class Delete
 * @property $id
 * @package fl\cms\entities\dynamic_classes
 */
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
        $queryParams = $this->setQueryParams($this->id);
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] = 'ok') {
            $this->_properties[] = [];
        } else {
            $this->exceptionHandling('3', ['Test']);
        }
        
    }
    
     private function setQueryParams(int $id): array
    {
        $data = [
            'action' => 'class_delete',
            'params' => 
            [
                'id' => (int)$id
            ]
        ];
        return $data;
    }
}