<?php
namespace fl\cms\models;

use Yii;
use yii\base\Model;
use fl\cms\helpers\user\Session;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $result;
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        $this->result['response'] = $this->sendQueryLogin([
                'email' => $this->username,
                'pass' => $this->password,
            ]);
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $session = Session::init($this->result['response']['params']['token']);
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername('admin@freelemur.com');
        }
        return $this->_user;
    }

    /**
     * @param array $data
     * @return array
     */
    private function sendQueryLogin(array $data): array
    {
        $url = 'http://snu1743.freelemur.com:55502/login';
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
