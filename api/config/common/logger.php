<?php

declare(strict_types=1);

use Api\Infrastructure\Framework\ErrorHandler\LogHandler;
use Api\Infrastructure\Framework\ErrorHandler\LogPhpHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    LoggerInterface::class => function(ContainerInterface $container) {
        $config = $container->get('config')['logger'];
        $logger = new \Monolog\Logger('API');
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($config['file']));
        return $logger;
    },

    'errorHandler' => function (ContainerInterface $container) {
        return new LogHandler(
            $container->get(LoggerInterface::class),
            $container->get('settings')['displayErrorDetails']
        );
    },

    'phpErrorHandler' => function (ContainerInterface $container) {
        return new LogPhpHandler(
            $container->get(LoggerInterface::class),
            $container->get('settings')['displayErrorDetails']
        );
    },

    'config' => [
        'logger' => [
            'file' => 'var/log/app.log',
        ]
    ]
];