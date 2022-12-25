<?php

$container['logger'] = function ($c) {
    return new App\Loggers\Logger(
        [
            'path' => 'logs/',
            'name' => 'app',
            'name_format' => '',
            'extension' => 'log',
            'message_format' => '[%label%] %date% %message%'
        ]
    );
};

$app->add('logger');