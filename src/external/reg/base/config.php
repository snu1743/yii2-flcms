<?php
return [
    'api' => [
        'actions' => [
            'domains_add_alias' => 'fl\cms\external\reg\AddAlias',
            'domains_add_cname' => 'fl\cms\external\reg\AddCname'
        ]
    ],
    'security' => [
        'key_path' => '/home/developer/reg.ru/api.key',
        'crt_path' => '/home/developer/reg.ru/api.crt'
    ]
];