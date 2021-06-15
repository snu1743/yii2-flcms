<?php


namespace fl\cms\apps\base;


class AppsInitiator extends MainInitiator
{
    /**
     * Процесс инициализации CMS приложения.
     */
    public function process(): void
    {
        do {
            $this->appParams = [];
            $appsFound = preg_match('/(<fl-back-app.*?\/>)/si', $this->page, $matches);
            if ($appsFound) {
                $this->setAppParams($matches);
                if($this->setApp()){
                    $result = $this->app->exec();
                }
                $this->page = preg_replace('/(<fl-back-app.*?\/>)/si', $result, $this->page, 1);
            }
        } while ($appsFound);
    }

    /**
     * @param $matches
     */
    private function setAppParams($matches): void
    {
        $xml = simplexml_load_string($matches[1]);
        foreach ($xml->attributes() as $attr => $value) {
            $this->appParams[$attr] = (string)$value;
        }
        if (isset($this->appParams['id']) && isset($this->config['fl_cms']['apps'][$this->appParams['id']])) {
            $this->appParams = array_merge($this->appParams, $this->config['fl_cms']['apps'][$this->appParams['id']]);
        }
    }

    /**
     * @return bool
     */
    private function setApp(): bool
    {
        if (!isset($this->appParams['class']) && !is_string($this->appParams['class'])) {
            return false;
        }
        $class = $this->appParams['class'];
        if(class_exists($class)){
            $this->app = new $class($this->page, $this->pageData, $this->appParams);
            return true;
        }
        return false;
    }
}