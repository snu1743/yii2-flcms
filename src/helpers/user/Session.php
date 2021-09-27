<?php


namespace fl\cms\helpers\user;

use yii;
use fl\cms\repositories\project\CmsProjectDetail;
use fl\cms\repositories\user\CmsUserRepository;
use fl\cms\repositories\project\CmsProjectTrees;
use fl\cms\helpers\encryption\FLHashEncrypStatic;
use fl\cms\entities\security\InitSessionException;
use fl\cms\entities\Main as Entity;
use fl\cms\helpers\url\UrlBase;

class Session
{
    private static $session;
    private static $flCmsParams;

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
        self::setProjectData();
        self::setTokenData($token);
        self::setUserData();
        Yii::$app->session->set('fl_cms', self::$flCmsParams['fl_cms']);
        Yii::$app->session->set('ie_services_token', self::$flCmsParams['ie_services_token']);
        return Yii::$app->session;
    }

    /**
     * @return mixed
     * @throws yii\base\Exception
     * @throws yii\db\Exception
     */
    private static function setProjectData()
    {
        $domain = UrlBase::getProjectDomainName();
        self::$flCmsParams['fl_cms']['project_details'] = CmsProjectDetail::get(['cms_project_domain' => $domain]);
        self::$flCmsParams['fl_cms']['trees'] = CmsProjectTrees::get(['cms_project_domain' => $domain]);
        foreach (self::$flCmsParams['fl_cms']['trees'] as $tree){
            self::$flCmsParams['fl_cms']['tree_ids'][] = $tree['cms_tree']['id'];
        }
        return self::$flCmsParams;
    }

    /**
     * Получение данных пользователя
     */
    private static function setUserData()
    {
        if (isset(self::$flCmsParams['ie_services_token']['public']['user']['instance_id'])) {
            $tokenData['user_id'] = self::$flCmsParams['ie_services_token']['public']['user']['instance_id'];
            $tokenData['cms_user'] = self::$flCmsParams['ie_services_token']['public']['user'];
        } else {
            $tokenData = [
                'user_id' => Yii::$app->user->id
            ];
        }
        $apiToken = FLHashEncrypStatic::encrypt(json_encode($tokenData, JSON_UNESCAPED_UNICODE));
        setcookie('e_token', $apiToken, 0, '/', '.' . $_SERVER['HTTP_HOST']);
        self::$flCmsParams['fl_cms']['user'] = self::$flCmsParams['ie_services_token']['public']['user'];
        self::$flCmsParams['fl_cms']['user_id'] = self::$flCmsParams['ie_services_token']['public']['user']['instance_id'];
        self::$flCmsParams['fl_cms']['group_ids'] = CmsUserRepository::getCmsGroupUserBindIdList(['user_id' => self::$flCmsParams['fl_cms']['user_id']]);
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
       self::$flCmsParams['ie_services_token'] = $token;
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

    /**
     * @return int
     */
    public static function getUserId(): int
    {
        $session = yii::$app->session;
        if(isset($session['fl_cms']['user_id'])){
            return $session['fl_cms']['user_id'];
        }
        return 0;
    }

    /**
     * @return array
     */
    public static function getGroupIds(): array
    {
        $session = yii::$app->session;
        if(isset($session['fl_cms']['group_ids'])){
            return $session['fl_cms']['group_ids'];
        }
        return [];
    }

    /**
     * @return int
     * @throws yii\base\Exception
     * @throws yii\db\Exception
     */
    public static function getProgectId(): int
    {
        $session = yii::$app->session;
        if(!isset($session['fl_cms']['project_details']['cms_project']['id'])){
            self::setProjectData();
            $session['fl_cms'] = self::$flCmsParams['fl_cms'];
        }
        return $session['fl_cms']['project_details']['cms_project']['id'];
    }

    /**
     * @return int
     * @throws yii\base\Exception
     * @throws yii\db\Exception
     */
    public static function getMainTreeId(): int
    {
        $session = yii::$app->session;
        $s = $session['fl_cms'];
        if(!isset($session['fl_cms']['project_details']['cms_tree']['id'])){
            self::setProjectData();
            $session['fl_cms'] = self::$flCmsParams['fl_cms'];
        }
        return $session['fl_cms']['project_details']['cms_tree']['id'];
    }
}