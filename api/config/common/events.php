<?php

declare(strict_types=1);

use Api\Infrastructure\Model\EventDispatcher\Listener;
use Api\Infrastructure\Model\EventDispatcher\SyncEventDispatcher;
use Psr\Container\ContainerInterface;

return [
    Api\Model\EventDispatcher::class => function (ContainerInterface $container) {
        return new SyncEventDispatcher(
            $container,
            []
        );
    },
];
