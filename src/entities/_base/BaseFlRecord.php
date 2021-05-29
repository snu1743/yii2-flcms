<?php

namespace fl\cms\entities\base;

use yii;
use yii\base\Model;
use yii\base\ModelEvent;
use fl\cms\entities\base\modifiers\base\Modifiers;
use fl\cms\entities\Main;
use fl\cms\base\person\Session;

abstract class BaseFlRecord extends Model
{
    const EVENT_BEFORE_PERFORM = 'beforePerform';
    const FN_INIT_MODEL = 'initModel';
    const EVENT_AFTER_PERFORM = 'afterPerform';
    public $_config;
    public $_params;
    private $_attributes = [];
    public $_response;
    public $_actionName;
    public $_properties;
    public $_result;
    public $_additional_data;

    public function __construct( string $actionName = null, array $config = [], array $params = [])
    {
        parent:: __construct();
        $this->_actionName = $actionName;
        $this->_config = $config;
        $this->_params = $params;
        $this->_response = new ApiResponse();
        $this->initialization($actionName);
        return $this;
    }
    
    public function initialization(string $actionName, $callback = null, $event = false): BaseFlRecord
    {
        $this->loadProperties($actionName);
        $this->applyPropertiesModifiersIn();
        $this->validate();
        $modelEvent = new ModelEvent();
        $this->trigger($event['before'] ?? self::EVENT_BEFORE_PERFORM, $modelEvent);
        $callback = ($callback) ? $callback : self::FN_INIT_MODEL;
        $this->$callback();
        $this->checkErrors();
        $this->applyPropertiesModifiersOut();
        $this->setEntityProperties();
        $this->trigger($event['before'] ?? self::EVENT_AFTER_PERFORM, $modelEvent);
        $this->_response->setResult($this->_properties, $this->_additional_data);
        return $this;
    }
    
    public function exceptionHandling( string $id, array $params, array $config = []): void
    {
        $this->addError($id, $params[0]);
    }


    private function applyPropertiesModifiersIn(): void
    {
        if(!isset($this->_attributes) || !isset($this->_config[$this->_params['action_name']]['properties'])) {
            return;
        }
        $this->_attributes = Modifiers::apply($this->_config, $this->_attributes, $this->_params, 'in');
    }
    
    private function applyPropertiesModifiersOut(): void
    {

        if(!isset($this->_properties) || !isset($this->_config[$this->_params['action_name']]['properties'])) {
            return;
        }
        $this->_properties = Modifiers::apply($this->_config, $this->_properties, $this->_params);
    }
    
    private function setEntityProperties(): void
    {
        if(!isset($this->_properties) || !isset($this->_config[$this->_params['action_name']]['properties'])) {
            return;
        }
        if(isset($this->_config[$this->_params['action_name']]['properties'][0])) {
            foreach ($this->_properties as $number => $property) {
                foreach ($property as $key => $item) {
                    if(!in_array($key, $this->_config[$this->_params['action_name']]['properties'])) {
                        unset($this->_properties[$number][$key]);
                    }
                }
            }
        } else {
            foreach ($this->_properties as $number => $property) {
                foreach ($property as $key => $item) {
                    if(!array_key_exists($key, $this->_config[$this->_params['action_name']]['properties'])) {
                        unset($this->_properties[$number][$key]);
                    }
                }
            }
        }
    }
    
    public function checkErrors() : void
    {
        if($this->hasErrors()) {
            $this->_response->setStatus($this->_response::FAILURE)
                            ->setErrors($this->getErrors());
        } else {
            $this->_response->setStatus($this->_response::SUCCESS);
        }
    }


    public function getRules($actionName = null)
    {
        if(is_array($this->_config[$this->_actionName]['rules'])) {
            return $this->_config[$this->_actionName]['rules'];
        }
        return [];
    }
    
    public function getParams($actionName)
    {
        return $this->_params;
    }
    
    public function initModel()
    {
        echo 'initModel';
    }

    /**
     * Load properties into the model
     * @param string $actionName
     */
    private function loadProperties(string $actionName): void
    {
        $keys = $this->setKeyForAttributes($this->getRules($actionName));
        foreach ($keys as $key => $value) {
            $this->_attributes[$value] = null;
        }
        foreach ($this->_params as $key => $value) {
            $this->$key = $value;
        }
    }
    
    private function setKeyForAttributes(array $keys,  array $arrKeys = [])
    {
        foreach ($keys as $key => $value) {
            if(is_string($value) && !is_numeric($value)) {
                $arrKeys[] = $value;
            }
            if(is_array($value))  {
                $arrKeys = $this->setKeyForAttributes($value, $arrKeys);
            }
            
        }
        return $arrKeys;
    }

    public function __get($name)
    {
        if (isset($this->_attributes[$name]) || array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }

        if ($this->hasAttribute($name)) {
            return null;
        }
        $value = parent::__get($name);

        return $value;
    }
    
    public function &getPropertyByReference($name)
    {
        if (isset($this->_attributes[$name]) || array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }

        if ($this->hasAttribute($name)) {
            return null;
        }
        $value = parent::__get($name);

        return $value;
    }

    public function __set($name, $value)
    {
        $this->_attributes[$name] = $value;
        if ($this->hasAttribute($name)) {
            $this->_attributes[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }
    
    public function &setPropertyByReference($name, $value)
    {
        $this->_attributes[$name] = $value;
        if ($this->hasAttribute($name)) {
            $this->_attributes[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }
    
    public function hasAttribute(string $name)
    {
        return isset($this->_attributes[$name]) || in_array($name, $this->attributes(), true);
    }
    
    public function getAllAttributes(): array
    {
        return $this->_attributes;
    }

    public function performActionEntity(array $request): array
    {
        return Main::perform($request);
    }

    private function getApiToken()
    {
        $token = Session::get('token', false);
        if($token['public']['exp'] < time()) {
            return false;
        }
        return $token['base_64'];
    }
    
    public function sendQuery(array $data): array
    {
        $token = $this->getApiToken();
        $url = 'http://freelemur.com:55502/api';
        $jsonBillingData = json_encode($data, JSON_UNESCAPED_UNICODE);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBillingData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonBillingData),
                "Authorization: Bearer $token"
            )
        );
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }
}
