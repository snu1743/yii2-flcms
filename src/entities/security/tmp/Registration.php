<?php

namespace fl\cms\entities\page;

use Yii;
use fl\cms\entities\base\BaseFlRecord;
use luya\cms\admin\Module;
use luya\cms\models\Nav;
//use luya\cms\models\NavItem;
//use luya\cms\models\NavItemPage;
//use luya\cms\models\NavItemRedirect;
//use luya\cms\models\NavItem;
//use luya\cms\models\Log;

class Registration extends BaseFlRecord
{
    private const ACTION_NAME = 'create';
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->getRules(self::ACTION_NAME);
    }
    
    public function getActionName() {
        return self::ACTION_NAME;
    }
    
    public function initModel()
    {
        $this->person();
    }
    
    private function person()
    {
        echo __METHOD__;
        exit;
    }
}