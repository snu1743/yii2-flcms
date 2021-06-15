<?php

namespace fl\cms\entities\dynamic_classes;

use fl\cms\helpers\encryption\FLHashEncrypStatic as FLHashEncryp;
use Yii;
use fl\cms\entities\base\BaseFlRecord;

class GetList extends BaseFlRecord
{
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
    public function initModel()
    {
        if(!isset($this->user_id)) {
            $session = Yii::$app->session;
            $this->user_id = $session['person']['id'];
        }
        $queryParams = $this->setQueryParams($this->user_id, $this->pagination_page, $this->pagination_row_count);
        $this->_result = $this->sendQuery($queryParams);
        $defaultData = null;
        if(!$this->_result['params']['rows']) {
////            $defaultData['#type'] = 'string';
//            $defaultData['e_entity_class_id'] = $this->e_entity_class_id;
//            $defaultData['description'] = '';
//            $defaultData['id'] = null;
//            $defaultData['name'] = '';
//            $defaultData['the_date'] = '';
//            $defaultData['type'] = 2;
//            $defaultData['e_user_id'] = FLHashEncryp::encrypt($this->user_id ?? 0);
//            $defaultData['uuid'] = '';
        }
        if(isset($this->_result['status']) && $this->_result['status'] === 'ok') {
            $this->_properties = $this->_result['params']['rows'];
            $this->_additional_data['pagination_count'] = $this->_result['params']['pagination_count'];
            $this->_additional_data['pagination_page'] = $this->_result['params']['pagination_page'];
            $this->_additional_data['pagination_row_count'] = $this->pagination_row_count;
            $this->_additional_data['default_data'] = $defaultData;
        } else {
            $this->exceptionHandling('1', [json_encode($this->_result)]);
        }

    }

    /**
     * @param int $user_id
     * @param int $pagination_page
     * @param int $pagination_row_count
     * @return array
     */
    private function setQueryParams(int $user_id, int $pagination_page, int $pagination_row_count): array
    {
        $data =  [
            'action' => 'classes_list',
            'params' =>
                [
                    'props' => 'name, description',
                    'where' => "owner_id = $user_id",
                    'order' => '',
                    'pagination_page' => (int)$pagination_page,
                    'pagination_row_count' => (int)$pagination_row_count
                ]
        ];
        return $data;
    }
}