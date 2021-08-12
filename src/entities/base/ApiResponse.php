<?php


namespace fl\cms\entities\base;

use yii;

class ApiResponse
{
    public const SUCCESS = 'success';
    public const FAILURE = 'failure';
    private $data;

    public function setStatus(string $status)
    {
        $this->data['status'] = $status;
        return $this;
    }

    public function setResult(?array $properties, ?array $additional_data = null)
    {
        $this->data['properties'] = $properties;
        $this->data['additional_data'] = $additional_data;
        return $this;
    }

    public function setConfig(array $config)
    {
        $this->data['config'] = $config;
        return $this;
    }

    public function setCode(int $code)
    {
        $this->data['code'] = $code;
        return $this;
    }

    public function setErrors(array $params)
    {
        $this->data['errors'] = $params;
        return $this;
    }

    public function getAsArray()
    {
        return $this->data;
    }

    public function get()
    {
        $response = Yii::$app->getResponse();
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $this->data;
        return $response;
    }
}