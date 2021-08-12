<?php

namespace fl\cms\external\base;

interface ApiItemInterfaces
{
    public function setRequestData(array $params): ?array;

    public function send(array $params): ?array;
}