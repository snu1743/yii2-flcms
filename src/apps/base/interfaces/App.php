<?php


namespace fl\cms\apps\base;


use fl\cms\apps\base\interfaces\MainInterface;

abstract class Main implements interfaces\MainInterface
{
    /**
     * @var string
     */
    public $page = '';

    /**
     * @var array
     */
    public $pageData = [];
    public $config = [];

    /**
     * @var Main
     */
    private static $initiator;
    /**
     * @var
     */
    private static $app;

    /**
     * @param string $page
     * @param array $pageData
     * @return string
     */
    public static function init(string $page, array $pageData): string
    {
        $class = get_called_class();
        self::$initiator = new $class();
//        self::$initiator->config = require_once ;
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