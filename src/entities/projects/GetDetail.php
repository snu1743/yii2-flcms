<?php


namespace fl\cms\entities\projects;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use fl\cms\repositories\project\CmsProjectTrees;
use fl\cms\repositories\project\CmsProjectDetail;
use \fl\cms\helpers\page\Create as PageCreate;
use yii\base\Exception;


class GetDetail extends BaseFlRecord
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
        $properties = [
            'details' => CmsProjectDetail::get(['cms_project_domain' => $this->domain]),
            'trees' => CmsProjectTrees::get(['cms_project_domain' => $this->domain])
        ];
        $this->_properties = $properties;
    }
}