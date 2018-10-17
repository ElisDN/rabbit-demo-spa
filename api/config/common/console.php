<?php

use Api\Console\Command;
use Kafka\ConsumerConfig;
use Kafka\Producer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    Command\Kafka\ProduceCommand::class => function (ContainerInterface $container) {
        return new Command\Kafka\ProduceCommand(
            $container->get(Producer::class)
        );
    },
    Command\Kafka\ConsumeCommand::class => function (ContainerInterface $container) {
        return new Command\Kafka\ConsumeCommand(
            $container->get(LoggerInterface::class),
            $container->get(ConsumerConfig::class)
        );
    },

    'config' => [
        'console' => [
            'commands' => [
                Command\Kafka\ProduceCommand::class,
                Command\Kafka\ConsumeCommand::class,
            ],
        ],
    ],
];
