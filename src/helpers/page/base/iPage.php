<?php


namespace fl\cms\helpers\page\base;

/**
 * Interface iPage
 * @package fl\cms\helpers\page\base
 */
interface iPage
{
    public static function exec(array $params):array;
    public function setParams(array $params):void;
    public function process():void;
    public function getResult():array;
    public function getRules():array;
}