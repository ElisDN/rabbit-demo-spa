<?php

declare(strict_types=1);

use Api\Infrastructure\Model\EventDispatcher\Listener;
use Api\Infrastructure\Model\EventDispatcher\SyncEventDispatcher;
use Api\Model\User as UserModel;
use Api\Model\Video as VideoModel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;

return [
    Api\Model\EventDispatcher::class => function (ContainerInterface $container) {
        return new SyncEventDispatcher(
            $container,
            [
                UserModel\Entity\User\Event\UserCreated::class => [
                    Listener\User\CreatedListener::class,
                ],
                VideoModel\Entity\Video\Event\VideoCreated::class => [
                    Listener\Video\VideoCreatedListener::class,
                ],
            ]
        );
    },

    Listener\User\CreatedListener::class => function (ContainerInterface $container) {
        return new Listener\User\CreatedListener(
            $container->get(Swift_Mailer::class),
            $container->get('config')['mailer']['from']
        );
    },

    Listener\Video\VideoCreatedListener::class => function (ContainerInterface $container) {
        return new Listener\Video\VideoCreatedListener(
            $container->get(AMQPStreamConnection::class)
        );
    },
];
