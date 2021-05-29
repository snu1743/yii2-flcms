<?php


namespace fl\cms\entities\dynamic_classes;

use Yii;
use fl\cms\entities\base\BaseFlRecord;

/**
 * Class MethodsList
 * @package fl\cms\entities\dynamic_classes
 * @property integer $user_id
 * @property integer $entity_class_id
 * @property integer $props
 * @property integer $result
 */
class MethodsList extends BaseFlRecord
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
    public function initModel(): void
    {
        $queryParams = $this->setQueryParams($this->entity_class_id);
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] === 'ok') {
            $this->_properties = $this->_result['params']['rows'];
        } else {
            $this->exceptionHandling('1', [json_encode($this->_result)]);
        }
    }

    /**
     * Set query params
     * @param int $entity_class_id
     * @param string|null $props
     * @return array
     */
    private function setQueryParams(int $entity_class_id, ?string $props = null): array
    {
        $data = [
            'action' => 'methods_list',
            'params' => [
                'class_id' => $entity_class_id,
            ]
        ];
        if(is_string($props)) {
            $data['params']['props'] = $props;
        }
        return $data;
    }
}