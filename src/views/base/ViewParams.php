<?php


namespace fl\cms\views\base;

use fl\cms\helpers\encryption\FLHashEncrypStatic as FLHashEncryp;

class ViewParams
{
    public static function set(array $data): array
    {
        \Yii::$app->params['page']['data'] = $data;
        \Yii::$app->params['page']['e_cms_page'] = FLHashEncryp::encrypt(json_encode($data['cms_page'], JSON_UNESCAPED_UNICODE));
        \Yii::$app->params['page']['e_id'] = FLHashEncryp::encrypt($data['cms_page']['id']);
        if(isset($data['cms_page']['parent_id'])){
            \Yii::$app->params['page']['e_parent_id'] = FLHashEncryp::encrypt($data['cms_page']['parent_id']);
        }
        $params['data'] = $data;
        $params['body'] = (isset($data['cms_page_content']['body']) && is_string($data['cms_page_content']['body'])) ? $data['cms_page_content']['body'] : '';
        return $params;
    }
}