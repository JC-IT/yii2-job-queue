<?php
declare(strict_types=1);

use yii\db\Connection;
use yii\web\Application;

return [
    'class' => Application::class,
    'id' => 'yii2-job-queue-test',
    'basePath' => dirname(dirname(__DIR__)),
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => 'sqlite::memory:'
        ],
    ]
];
