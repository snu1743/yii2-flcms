<?php


namespace fl\cms\entities\dynamic_classes;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use fl\cms\entities\dynamic_classes\base\ConfigBuilder;
/**
 * Class GetConfig
 * @package fl\cms\entities\dynamic_classes
 * @property integer $user_id
 * @property integer $entity_class_id
 */
class GetConfig extends BaseFlRecord
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
            $configBasePath = __DIR__ . "/../dynamic_instances/config_base.php";
            $configBase = require_once $configBasePath;
            $configInstance = json_decode(json_decode($this->_result['params']['config'], true),true);
            if(is_array($configInstance)) {
//                $config = array_merge($configBase, $configInstance);
                $config = $configInstance;
            } else {
                $config = [];
//                $config =$configBase;
            }
//            $config['_methods_list'] = $this->getMethodsList();
            $config = ConfigBuilder::init($config, $this->entity_class_id);
            $this->_properties[] = [
                'content' => $config,
                'params' => $this->entity_class_id
            ];
        } else {
            $this->exceptionHandling('8', [json_encode($this->_result)]);
        }
        
    }
    
    private function setQueryParams(int $entity_class_id): array
    {
        return [
                "action" => "class_get_config",
                "params" => [
                "id" => $entity_class_id
            ]
        ];
    }

    private function getMethodsList()
    {
        $request = [
            'action_name' => 'methods_list',
            'entity' => 'dynamic_classes',
            'entity_class_id' => $this->entity_class_id
        ];
        $result = $this->performActionEntity($request);
        return $result['properties'];
    }
}