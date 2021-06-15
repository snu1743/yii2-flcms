<?php

namespace fl\cms\entities\dynamic_instances;

use Yii;
use fl\cms\helpers\encryption\FLHashEncrypStatic as FLHashEncryp;
use fl\cms\entities\base\BaseFlRecord;

class InstancesList extends BaseFlRecord
{
    private const ACTION_NAME = 'instances_list';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules(self::ACTION_NAME);
    }
    
    public function initModel()
    {
        if(!isset($this->user_id)) {
            $session = Yii::$app->session;
            $this->user_id = $session['fl_cms']['user_id'];
        }
        $queryParams = $this->setQueryParams($this->user_id, $this->entity_class_id, $this->pagination_page, $this->pagination_row_count);
        $this->_result = $this->sendQuery($queryParams);
        $defaultData = null;
        if(count($this->_result['params']['rows']) === 0) {
//            $defaultData['#type'] = 'string';
            $defaultData['e_entity_class_id'] = $this->e_entity_class_id;
            $defaultData['description'] = '';
            $defaultData['id'] = null;
            $defaultData['name'] = '';
            $defaultData['the_date'] = '';
            $defaultData['type'] = 2;
            $defaultData['e_user_id'] = FLHashEncryp::encrypt($this->user_id);
            $defaultData['uuid'] = '';
        }
        if(isset($this->_result['status']) && $this->_result['status'] = 'ok') {
            $this->_properties = $this->_result['params']['rows'];
            $this->_additional_data['pagination_count'] = $this->_result['params']['pagination_page'];
            $this->_additional_data['pagination_page'] = $this->_result['params']['pagination_page'] - 1;
            $this->_additional_data['pagination_row_count'] = $this->pagination_row_count;
            $this->_additional_data['default_data'] = $defaultData;
        } else {
            $this->exceptionHandling('9', [json_encode($this->_result)]);
        }
    }
    
    private function setQueryParams(int $user_id, int $entity_class_id, int $pagination_page, int $pagination_row_count): array
    {
        $data = [
            'user_id' => (int)$user_id,
            'action' => 'instances_list',
            'params' => 
            [
                'class_id' => (int)$entity_class_id,
                'state_id' => 0,
                'props' => '',
                'where' => '',
                'order' => '',
                'pagination_page' => $pagination_page,
                'pagination_row_count' => $pagination_row_count
            ]
        ];
        return $data;
    }
}