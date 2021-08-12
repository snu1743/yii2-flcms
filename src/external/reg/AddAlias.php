<?php

namespace fl\cms\external\reg;

class AddAlias extends base\Request
{
    public function prepareRequest()
    {
        $this->setRequestData();
    }

    public function setRequestData($params): array
    {
        $params['request_data'] =
            [
                'domain_name' => 'freelemur.com',
                'dname' => 'freelemur.com',
                'input_format' => 'plain',
                'username' => 'anatoliysmirnov79@yandex.ru',
                'endpoint' => 'https://api.reg.ru/api/regru2/zone/update_records'
            ];
        return $params['request_data'];
    }
}