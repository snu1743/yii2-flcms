<?php

namespace frontend\assets;

use yii;
use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class FlAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [];
    public $configAssets;
    public $assetsListName;
    public static $additional;


    public function setAssets() {
        
        if(isset($this->configAssets['assets_list'][$this->assetsListName])) {
            if(isset($this->configAssets['assets_list'][$this->assetsListName]['css'])) {
                $this->set('css', $this->configAssets['assets_list'][$this->assetsListName]['css']);
            }
            if(isset($this->configAssets['assets_list'][$this->assetsListName]['js'])) {
                $this->set('js', $this->configAssets['assets_list'][$this->assetsListName]['js']);
            }
            if(isset($this->configAssets['assets_list']['default']['depends'])) {
                $this->set('depends', $this->configAssets['assets_list']['default']['depends']);
            }
        } else {
            if(isset($this->configAssets['assets_list']['default']['css'])) {
                $this->set('css', $this->configAssets['assets_list']['default']['css']);
            }
            if(isset($this->configAssets['assets_list']['default']['js'])) {
                $this->set('js', $this->configAssets['assets_list']['default']['js']);
            }
            if(isset($this->configAssets['assets_list']['default']['depends'])) {
                $this->set('depends', $this->configAssets['assets_list']['default']['depends']);
            }
        }
    }
    
    public function set($type, $list) {
        
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
//            echo '<pre>';
//            print_r($this->configAssets[$type][$value]);
//            echo '</pre>';
        }

        return;
    }
    
    public function setDefault($type) {
        
        foreach ($this->configAssets['assets_list']['default'][$type] as $key => $value) {
            
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
    
    
    
    public static function register($view, $additional = null)
    {
        self::$additional = $additional;
        return parent::register($view);
    }
}