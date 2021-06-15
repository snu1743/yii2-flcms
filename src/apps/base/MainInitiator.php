<?php


namespace fl\cms\apps\base;

use yii;
//use fl\cms\apps\base\interfaces\MainInterface;

abstract class MainInitiator implements interfaces\MainInterface
{
    /**
     * @var interfaces\AppInterface
     */
    public $app;
    /**
     * @var Main
     */
    private static $initiator;

    public $page = '';

    public $appParams = [];

    public $config = [];

    public $pageData = [];

    /**
     * @param string $page
     * @param array $pageData
     * @return string
     */
    public static function init(string $page, array $pageData): string
    {
        $class = get_called_class();
        self::$initiator = new $class();
        self::$initiator->config = yii::$app->params;
        self::$initiator->page = $page;
        self::$initiator->pageData = $pageData;
        self::$initiator->process();
        return self::$initiator->getResult();
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->page;
    }
}