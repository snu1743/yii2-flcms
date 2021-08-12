<?php


namespace fl\cms\helpers\project\base;

/**
 * Interface iPage
 * @package fl\cms\helpers\project\base
 */
interface iProject
{
    public static function exec(array $params):array;
    public function setParams(array $params):void;
    public function process():void;
    public function getResult():array;
    public function getRules():array;
}