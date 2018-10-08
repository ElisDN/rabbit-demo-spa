<?php

use Api\Console\Command;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    Command\FixtureCommand::class => function (ContainerInterface $container) {
        return new Command\FixtureCommand(
            $container->get(EntityManagerInterface::class),
            'src/Data/Fixture'
        );
    },

    'config' => [
        'console' => [
            'commands' => [
                Command\FixtureCommand::class,
            ],
        ],
    ],
];
