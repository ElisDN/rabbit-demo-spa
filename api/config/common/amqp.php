<?php

declare(strict_types=1);

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;

return [
    AMQPStreamConnection::class => function (ContainerInterface $container) {
        $config = $container->get('config')['amqp'];
        return new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['username'],
            $config['password'],
            $config['vhost']
        );
    },

    'config' => [
        'amqp' => [
            'host' => getenv('API_AMQP_HOST'),
            'port' => getenv('API_AMQP_PORT'),
            'username' => getenv('API_AMQP_USERNAME'),
            'password' => getenv('API_AMQP_PASSWORD'),
            'vhost' => getenv('API_AMQP_VHOST'),
        ]
    ]
];