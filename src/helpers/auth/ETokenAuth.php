<?php


namespace fl\cms\helpers\auth;

use yii\filters\auth\AuthMethod;
use fl\cms\helpers\encryption\FLHashEncrypStatic;
use common\models\User;

class ETokenAuth extends AuthMethod
{
    /**
     * @var string the HTTP header name
     */
    public $header = 'X-Api-Key';
    /**
     * @var string a pattern to use to extract the HTTP authentication value
     */
    public $pattern;
    /**
     * {@inheritdoc}
     */
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('X-Api-Key');

        if ($authHeader !== null) {
            if ($this->pattern !== null) {
                if (preg_match($this->pattern, $authHeader, $matches)) {
                    $authHeader = $matches[1];
                } else {
                    return null;
                }
            }
            $authData = json_decode(FLHashEncrypStatic::decrypt($authHeader), true);
            if(isset($authData['user_id'])){
                $identity = new User();
                $identity->id = $authData['user_id'];
                return $identity;
            }
        }
        return null;
    }
}
