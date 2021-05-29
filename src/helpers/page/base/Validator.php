<?php


namespace fl\cms\helpers\page\base;

use yii\db\ActiveRecord;
use  yii\base\Model;

class Validator extends Model
{
    private $_attributes = [];
    public $currentRules = [];

    /**
     * Validator constructor.
     * @param array $rules
     * @param array $params
     */
    public function __construct(array $rules = [], array $params)
    {
        parent::__construct();
        $this->currentRules = $rules;
        $this->loadParams($params);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->_attributes[$name];
    }

    /**
     * @param array $params
     */
    public function loadParams(array $params)
    {
        foreach ($this->currentRules as $rule) {
            if (!isset($rule[0])) {
                continue;
            }
            if (is_string($rule[0])) {
                $prop = $rule[0];
                $this->$prop = null;
            }
            if (is_array($rule[0])) {
                foreach ($rule[0] as $prop) {
                    if (is_string($prop)) {
                        $this->$prop = null;
                    }
                }
            }
        }
        $this->load($params, '');
    }

    /**
     * @return mixed
     */
    public function exec()
    {
        return $this->validate();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return $this->currentRules;
    }
}