<?php

namespace fl\cms\entities\page;

use yii\base\ModelEvent;
use fl\cms\entities\base\BaseFlRecord;
use fl\cms\entities\page\Create;

class Main extends BaseFlRecord

{
    public function perform(string $method, array $params, bool $config) {
        $class = __NAMESPACE__ . "\\" . ucfirst($method);
        new $class();
//        $callback = $callback ?? self::PERFORM_CALLBACK;
//        $eventBefore = $eventBefore ?? self::EVENT_BEFORE_PERFORM;
//        $eventAfter = $eventAfter ?? self::EVENT_AFTER_PERFORM;
//        return json_decode(static::initialization($data, $callback, $eventBefore, $eventAfter)['send'], true);
    }
}
