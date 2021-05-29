<?php

namespace fl\cms\assets;

use yii\web\AssetBundle;
use fl\cms\assets\FlRulesAssets;
use fl\cms\assets\FlConfigAssets;
use fl\cms\assets\FlAssets;

/**
 * Main application asset bundle.
 */
class FlMainAssets extends FlAssets
{
    public function init() {
        parent::init();
        $this->assetsListName = $this->getAssetsListName();
        $this->configAssets = [
            'css' => FlConfigAssets::$config['css'],
            'js' => FlConfigAssets::$config['js'],
            'depends' => FlConfigAssets::$config['depends'],
            'rules' => FlRulesAssets::$rules,
        ];
        $this->setAssets();
        return;
    }
}
