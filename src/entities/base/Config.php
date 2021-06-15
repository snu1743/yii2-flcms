<?php

namespace fl\cms\entities\base;

use fl\cms\entities\Main;
use fl\cms\helpers\encryption\FLHashEncrypStatic AS FLHashEncryp;
use fl\cms\entities\base\ApiResponse;
use yii\web\Response;

/**
 * Description of Config
 *
 * @author snu5998
 */
class Config   extends BaseFlRecord
{
    
    public static function get(array $request): array
    {
        if(isset($request['entity_class_id'])) {
            $entityClassId = $request['entity_class_id'];
        }
        if(isset($request['e_entity_class_id'])) {
            $entityClassId = FLHashEncryp::decrypt($request['e_entity_class_id']);
        }
        $request = [
            'action_name' => 'get_config',
            'entity' => 'dynamic_classes',
            'entity_class_id' => $entityClassId
        ];
        $result = Main::perform($request);
        return $result['properties'][0]['content'];
    }

    /**
     * Set config
     * @param array $config
     * @return array
     */
    public static function set(array $config, array $request): Response
    {
        $allowed = ['form', 'grid', 'action' , 'callback', 'app'];
        $allowed = is_array($config['public_configuration_blocks']) ? array_merge($allowed, $config['public_configuration_blocks']) : $allowed;
        foreach ($config as $action => $actionConfig) {
            foreach ($actionConfig as $key => $value) {
                if(!in_array($key, $allowed)) {
                    unset($config[$action][$key]);
                }
            }
        }
        Form::set($config, $request);
        $response = new ApiResponse();
        $response->setStatus($response::SUCCESS);
        $response->setConfig($config);
        return $response->get();
    }
}
