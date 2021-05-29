<?php

namespace fl\cms\entities\dynamic_entities;

use Yii;
use fl\cms\entities\base\BaseFlRecord;


class GetConfig extends BaseFlRecord
{
    
    private const ACTION_NAME = 'get_available_methods';
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules(self::ACTION_NAME);
    }
    
    /**
     * @return void
     */
    public function initModel(): void
    {
        if(!isset($this->user_id)) {
            $session = Yii::$app->session;
            $this->user_id = $session['person']['id'];
        }
        $queryParams = $this->setQueryParams($this->user_id);
        $this->_result = $this->sendQuery($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] === 'ok') {
            $this->_properties = $this->_result['params']['rows'];
        } else {
            $this->exceptionHandling('7', ['Test']);
        }
        
    }
    
    private function setQueryParams( int $user_id): array
    {
        $data = ["user_id" => $user_id,
                "action" => "get_available_methods",
                "params" => [
                "entity_id" => 2
            ]
        ];
//        print_r($data); exit;
        return $data;
    }
    
    /**
     * @param array $data
     * @return array
     */
    private function sendQuery(array $data): array
    {
        $url = 'http://freelemur.com:55502/actions';
        $jsonBillingData = json_encode($data, JSON_UNESCAPED_UNICODE);
        $ch = curl_init($url);                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBillingData);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonBillingData))                                                                       
        );
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }
}