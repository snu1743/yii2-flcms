<?php


namespace fl\cms\helpers\user;

use yii;
use fl\cms\helpers\encryption\FLHashEncrypStatic;
use fl\cms\entities\security\InitSessionException;

class Session
{
    private static $session;

    /**
     * @return bool
     */
    public static function check()
    {
        if (!Yii::$app->user->isGuest) {
            self::$session = Yii::$app->session;
            if (self::$session->get('fl_cms')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Инициализация работы сессии.
     */
    public static function init(string $token)
    {
        self::setTokenData($token);
        self::setUserData();
        return self::$session;
    }

    private static function setUserData()
    {
        if (isset(self::$session['ie_services_token']['public']['user']['instance_id'])) {
            $tokenData['user_id'] = self::$session['ie_services_token']['public']['user']['instance_id'];
            $tokenData['cms_user'] = self::$session['ie_services_token']['public']['user'];
        } else {
            $tokenData = [
                'user_id' => Yii::$app->user->id
            ];
        }
        $apiToken = FLHashEncrypStatic::encrypt(json_encode($tokenData, JSON_UNESCAPED_UNICODE));
        setcookie('e_token', $apiToken, 0, '/', '.' . $_SERVER['HTTP_HOST']);
        $flCms['e_token'] = $apiToken;
        $flCms['user'] = self::$session['ie_services_token']['public']['user'];
        $flCms['user_id'] = self::$session['ie_services_token']['public']['user']['instance_id'];
        self::$session->set('fl_cms', $flCms);
    }

    /**
     * @param string $tokenBase64
     * @return array
     * @throws InitSessionException
     */
    private static function setTokenData(string $tokenBase64): array
    {
        if (strlen($tokenBase64) < 64) {
            throw new InitSessionException('Authorization error');
        }
        $data = explode('.', $tokenBase64);
        $token['base_64'] = $tokenBase64;
        $token['type'] = json_decode(base64_decode($data[0]), true);
        $token['public'] = json_decode(base64_decode($data[1]), true);
        self::$session = Yii::$app->session;
        self::$session['ie_services_token'] = $token;
        return $token;
    }

    /**
     * @param string|null $param
     * @param bool $checkSession
     * @return false|mixed
     */
    public static function get(?string $param = null)
    {
        if (self::check()) {
            if (isset(self::$session[$param])) {
                return self::$session[$param];
            }
        }
        return false;
    }
}