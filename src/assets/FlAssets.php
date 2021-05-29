<?php

namespace fl\cms\assets;

use yii;
use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class FlAssets extends AssetBundle
{
    public $sourcePath = '@vendor';
    public $css = [];
    public $js = [];
    public $depends = [];
    public $configAssets;
    public $assetsListName;
    public static $additional;

    public function setAssets()
    {
        if(isset($this->configAssets['rules'][$this->assetsListName])) {
            if(isset($this->configAssets['rules'][$this->assetsListName]['css'])) {
                $this->set('css', $this->configAssets['rules'][$this->assetsListName]['css']);
            }
            if(isset($this->configAssets['rules'][$this->assetsListName]['js'])) {
                $this->set('js', $this->configAssets['rules'][$this->assetsListName]['js']);
            }
            if(isset($this->configAssets['rules']['default']['depends'])) {
                $this->set('depends', $this->configAssets['rules']['default']['depends']);
            }
        } else {
            if(isset($this->configAssets['rules']['default']['css'])) {
                $this->set('css', $this->configAssets['rules']['default']['css']);
            }
            if(isset($this->configAssets['rules']['default']['js'])) {
                $this->set('js', $this->configAssets['rules']['default']['js']);
            }
            if(isset($this->configAssets['rules']['default']['depends'])) {
                $this->set('depends', $this->configAssets['rules']['default']['depends']);
            }
        }
    }
    
    public function set($type, $list)
    {
        foreach ($list as $key => $value) {

            if($value === 'default') {
                $this->setDefault($type);
                continue;
            }
            if(isset($this->configAssets[$type][$value])) {
                if($type === 'css') {
                    $this->css[] = $this->configAssets[$type][$value];
                }
                if($type === 'js') {

                    $this->js[] = $this->configAssets[$type][$value];
                }
                if($type === 'depends') {
                    $this->depends[] = $this->configAssets[$type][$value];
                }
            }
        }
        return;
    }

    /**
     * @param string $type
     */
    public function setDefault(string $type): void
    {
        foreach ($this->configAssets['rules']['default'][$type] as $key => $value) {
            
            if(isset($this->configAssets[$type][$value])) {
                if($type === 'css') {
                    $this->css[] = $this->configAssets[$type][$value];
                }
                if($type === 'js') {
                    $this->js[] = $this->configAssets[$type][$value];
                }
                if($type === 'depends') {
                    $this->depends[] = $this->configAssets[$type][$value];
                }
            }
        }
    }

    /**
     * @param yii\web\View $view
     * @param null $additional
     * @return FlAssets
     */
    public static function register($view, $additional = null): FlAssets
    {
        self::$additional = $additional;
        return parent::register($view);
    }

    /**
     * @return string|null
     */
    public function getAssetsListName(): ?string
    {
        $requestResolve = Yii::$app->request->pathInfo;
        if(!$requestResolve) {
            return null;
        }
        return $requestResolve;
    }
}