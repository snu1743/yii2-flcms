<?php

namespace fl\cms\apps\items\pages\menu\main;

use yii;
use yii\helpers\Html;
use fl\cms\helpers\encryption\FLHashEncrypStatic as FLHashEncryp;
use fl\cms\helpers\url\UrlBase;
use fl\cms\helpers\page\PageConstants;


class App extends \fl\cms\apps\base\AppMain
{
    /**
     * @var array
     */
    public static $params = [];
    public static $accessRules = [
        'allowed_action_id_list' => [],
        'allowed_action_name_list' => [],
    ];

    /**
     * @return string
     */
    public function exec(): string
    {
        $this->setParams();
        return require_once __DIR__ . '/layout.php';
    }

    /**
     * Set params
     */
    private function setParams(): void
    {
        if(isset(Yii::$app->params['page']['data']['cms_page']['access_rules'])){
            self::$accessRules = Yii::$app->params['page']['data']['cms_page']['access_rules'];
        }
        self::$params['edit_url'] = Yii::$app->request->url;
        self::$params['edit_url'] = Yii::$app->request->url;
        self::$params['edit_url'] = explode('?', self::$params['edit_url']);
        self::$params['edit_url'] = self::$params['edit_url'][0] . '?cms-page-edit-mod=1';

        self::$params['home_page_https'] = UrlBase::getHome();
        $fullName = '';
    }

    /**
     * @return bool
     */
    public static function checkAccessMenuPages(): bool
    {
        if(yii::$app->user->isGuest){
            return false;
        }
        return true;
    }

    public static function checkAccessActionPageCreate(): bool
    {
        if(in_array(PageConstants::PAGE_ACTION_LIST['page_create'], self::$accessRules['allowed_action_id_list'])){
            return true;
        }
        return false;
    }

    public static function checkAccessActionPageUpdate(): bool
    {
        if(in_array(PageConstants::PAGE_ACTION_LIST['page_update'], self::$accessRules['allowed_action_id_list'])){
            return true;
        }
        return false;
    }

    public static function checkAccessActionPageDelete(): bool
    {
        if(in_array(PageConstants::PAGE_ACTION_LIST['page_delete'], self::$accessRules['allowed_action_id_list'])){
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function checkAccessMenuProjects(): bool
    {
        if(yii::$app->user->isGuest){
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function checkAccessMenuElements(): bool
    {
        if(yii::$app->user->isGuest){
            return false;
        }
        return true;
    }
}