<?php


namespace fl\cms\helpers\user;

use yii;
use fl\cms\helpers\encryption\FLHashEncrypStatic;

class Session
{
    private static $session;

    public static function check()
    {
        if (!Yii::$app->user->isGuest) {
            self::$session = Yii::$app->session;
            if(!self::$session->get('fl_cms')){
                self::init();
            }
        }
    }

    /**
     * Инициализация работы сессии.
     */
    private static function init()
    {
        self::setUserData();
    }

    private static function setUserData()
    {
        $tokenData = [
            'user_id' => Yii::$app->user->id
        ];
        $apiToken =  FLHashEncrypStatic::encrypt(json_encode($tokenData, JSON_UNESCAPED_UNICODE));
        setcookie('e_token', $apiToken, 0, '/', '.' . $_SERVER['HTTP_HOST']);
        $flCms['e_token'] = $apiToken;
        self::$session->set('fl_cms', $flCms);
    }
}