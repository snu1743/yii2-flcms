<?php


namespace fl\cms\entities\projects;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use \fl\cms\helpers\project\Create as ProjectCreate;
use \fl\cms\helpers\url\UrlBase;
use yii\base\Exception;


class Create extends BaseFlRecord
{
    /**
     * @return array|mixed
     */
    public function rules()
    {
        return  $this->getRules();
    }

    /**
     * @inheritdoc
     */
    public function initModel(): void
    {
        $params = [
            'acronym' => $this->acronym,
            'short_name' => $this->short_name,
            'name' => $this->name,
            'main_domain' => yii::$app->params['fl_cms']['domains']['main_domain'],
            'provider_prod' => yii::$app->params['fl_cms']['providers_settings']['prodaction'],
            'provider_dev' => yii::$app->params['fl_cms']['providers_settings']['develop'],
            'alias_primary' => $this->alias_primary,
            'alias_secondary' => $this->alias_secondary,
            'project_domain_primary' => $this->alias_primary . '.' . yii::$app->params['fl_cms']['domains']['main_domain'],
            'project_domain_secondary' => $this->alias_secondary . '.' . yii::$app->params['fl_cms']['domains']['main_domain']
        ];
        $this->_properties[] = ProjectCreate::exec($params);
    }

}