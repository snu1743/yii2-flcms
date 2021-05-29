<?php

namespace fl\cms\entities\dynamic_instances;

use Yii;
use fl\cms\entities\base\BaseFlRecord;

/**
 * Class InstanceDetails
 * @package fl\cms\entities\dynamic_instances
 * @property  $instance_id
 */
class InstanceDetails extends BaseFlRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules();
    }

    public function initModel()
    {
        $queryParams = $this->setQueryParams($this->instance_id);
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] = 'ok') {
            $this->_properties[] = $this->_result['params'];
        } else {
            $this->exceptionHandling('9', [json_encode($this->_result)]);
        }
    }

    private function setQueryParams(int $instance_id): array
    {
        $data = [
            'action' => 'instance_details',
            'params' =>
            [
                'id' => (int)$instance_id,
            ]
        ];
        return $data;
    }
}