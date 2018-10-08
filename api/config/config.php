<?php

declare(strict_types=1);

use Api\Http\Action;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;

return [
    'settings' => [
        'addContentLengthHeader' => false,
        'displayErrorDetails' => (bool)getenv('API_DEBUG'),
    ],

    Action\HomeAction::class => function () {
        return new Action\HomeAction();
    },

    EntityManagerInterface::class => function (ContainerInterface $container) {
        $params = $container['config']['doctrine'];
        $config = Setup::createAnnotationMetadataConfiguration(
            $params['metadata_dirs'],
            $params['dev_mode'],
            $params['cache_dir'],
            new FilesystemCache(
                $params['cache_dir']
            ),
            false
        );
        return EntityManager::create(
            $params['connection'],
            $config
        );
    },

    'config' => [
        'doctrine' => [
            'dev_mode' => true,
            'cache_dir' => 'var/cache/doctrine',
            'metadata_dirs' => ['src/Model/User/Entity'],
            'connection' => [
                'url' => getenv('API_DB_URL'),
            ],
        ],
    ],
];