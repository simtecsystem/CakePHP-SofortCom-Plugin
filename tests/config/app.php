<?php

use Cake\Cache\Engine\FileEngine;
use Cake\Database\Connection;
use Cake\Database\Driver\Mysql;
use Cake\Error\ExceptionRenderer;
use Cake\Log\Engine\FileLog;
use Cake\Mailer\Transport\MailTransport;

return
[
    'App' => [
        'namespace' => 'SofortCom\Test\TestApp',
        'fullBaseUrl' => 'http://example.com'
    ],

    'Datasources' => [
        'test' => [
            'url' => 'sqlite:///:memory:',
            'timezone' => 'UTC'
        ],
    ],

    'Security' => [
        'salt' => 'DO NOT USE THIS KEY IN PRODUCTION ENVIRONMENT'
    ]
];