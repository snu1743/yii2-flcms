<?php


namespace fl\cms\entities\pages;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use fl\cms\helpers\page\Delete as PageDelete;


class Delete extends BaseFlRecord
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
        $session = yii::$app->session;
        $params = json_decode($this->cms_page, true);
        $this->_properties[] = PageDelete::exec($params);
    }
}