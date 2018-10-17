<?php

use Api\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;

return [
    Command\Amqp\ProduceCommand::class => function (ContainerInterface $container) {
        return new Command\Amqp\ProduceCommand(
            $container->get(AMQPStreamConnection::class)
        );
    },
    Command\Amqp\ConsumeCommand::class => function (ContainerInterface $container) {
        return new Command\Amqp\ConsumeCommand(
            $container->get(AMQPStreamConnection::class)
        );
    },

    'config' => [
        'console' => [
            'commands' => [
                Command\Amqp\ProduceCommand::class,
                Command\Amqp\ConsumeCommand::class,
            ],
        ],
    ],
];
