<?php


namespace fl\cms\apps\base;


use fl\cms\apps\base\interfaces\MainInterface;

/**
 * Class AppMain
 * @package fl\cms\apps\base
 */
abstract class AppMain implements interfaces\AppInterface
{
    public $page = '';
    public $pageData = [];
    public $appParams = [];

    /**
     * AppMain constructor.
     * @param string $page
     * @param array $pageData
     * @param array $appParams
     */
    public function __construct(string $page, array $pageData, array $appParams)
    {
        $this->page = $page;
        $this->pageData = $pageData;
        $this->appParams = $appParams;
    }
}