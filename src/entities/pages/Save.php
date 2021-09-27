<?php


namespace fl\cms\entities\pages;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use fl\cms\helpers\page\Create as PageCreate;
use fl\cms\repositories\page\Content;
use yii\base\Exception;
use fl\cms\helpers\encryption\FLHashEncrypStatic as FLHashEncryp;



class Save extends BaseFlRecord
{
    /**
     * @return array|mixed
     */
    public function rules()
    {
        return $this->getRules();
    }

    /**
     * @inheritdoc
     */
    public function initModel(): void
    {
        $params['body'] = base64_decode($this->content);
        $params['page_data'] = json_decode($this->page_data,true);
        Content::update($params);
    }
}