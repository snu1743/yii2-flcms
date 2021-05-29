<?php

namespace fl\cms\entities\dynamic_instances;

use Yii;
use fl\cms\entities\base\BaseFlRecord;

class ExecMethod extends BaseFlRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules();
    }

    /**
     * Инициализация модели
     */
    public function initModel(): void
    {
        if(!isset($this->user_id)) {
            $session = Yii::$app->session;
            $this->user_id = $session['person']['id'];
        }
        $queryParams = $this->setQueryParams();
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] = 'ok') {
            $this->_properties[] = $this->_result;
        } else {
            $this->exceptionHandling('10', [json_encode($this->_result)]);
        }
    }

    /**
     * Получение данных для запроса
     * @return array
     */
    private function setQueryParams(): array
    {
        $data = [
            'action' => 'exec_method',
            'params' => $this->setParams()
        ];
        return $data;
    }

    /**
     * Получение params, для запроса
     * @return array
     */
    private function setParams(): array
    {
        $attr = $this->getAllAttributes();
        $params['method_id'] = (int)$this->_config['_methods_list'][$this->_actionName]['id'];
        $params['class_id'] = (int)$this->_config['_methods_list'][$this->_actionName]['class_id'];
        if(isset($attr['id']) && is_integer((int)$attr['id'])) {
            $params['instance_id'] = (int)$attr['id'];
        }
        foreach($this->_config['_properties'] as $prop){
            if(!isset($prop['name'])) {
                continue;
            }
            if(isset($attr[$prop['name']])) {
                $params[$prop['name']] = $attr[$prop['name']];
            }
        }
        return $params;
    }
}