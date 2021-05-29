<?php


namespace fl\cms\entities\security;

use Yii;
use fl\cms\entities\base\BaseFlRecord;

/**
 * Class MethodsList
 * @package fl\cms\entities\dynamic_classes
 * @property integer $email
 * @property integer $pass
 * @property integer $result
 */
class Login extends BaseFlRecord
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
        $queryParams = $this->setQueryParams($this->email, $this->pass);
        $this->_result = $this->sendQueryLogin($queryParams);
        if(isset($this->_result['status']) && $this->_result['status'] === 'ok') {
            $this->_properties[0] = $this->_result['params'];
        } else {
            $this->exceptionHandling('1', [json_encode($this->_result)]);
        }
    }

    /**
     * Set query params
     * @param int $user_id
     * @param int $entity_class_id
     * @param string|null $props
     * @return array
     */
    private function setQueryParams( string $email, string $pass): array
    {
        return [
            'email' => $email,
            'pass' => $pass,
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    private function sendQueryLogin(array $data): array
    {
        $url = 'http://freelemur.com:55502/login';
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