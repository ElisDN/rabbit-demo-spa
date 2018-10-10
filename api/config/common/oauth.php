<?php

declare(strict_types=1);

use Api\Model\User\Service\PasswordHasher;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server;
use Api\Infrastructure\Model\OAuth as Infrastructure;
use Psr\Container\ContainerInterface;

return [
    Server\AuthorizationServer::class => function (ContainerInterface $container)
    {
        $config = $container->get('config')['oauth'];

        $clientRepository = $container->get(Server\Repositories\ClientRepositoryInterface::class);
        $scopeRepository = $container->get(Server\Repositories\ScopeRepositoryInterface::class);
        $accessTokenRepository = $container->get(Server\Repositories\AccessTokenRepositoryInterface::class);
        $authCodeRepository = $container->get(Server\Repositories\AuthCodeRepositoryInterface::class);
        $refreshTokenRepository = $container->get(Server\Repositories\RefreshTokenRepositoryInterface::class);
        $userRepository = $container->get(Server\Repositories\UserRepositoryInterface::class);

        $server = new Server\AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            new Server\CryptKey($config['private_key_path'], null, false),
            $config['encryption_key']
        );

        $grant = new Server\Grant\AuthCodeGrant($authCodeRepository, $refreshTokenRepository, new \DateInterval('PT10M'));
        $server->enableGrantType($grant, new \DateInterval('PT1H'));

        $server->enableGrantType(new Server\Grant\ClientCredentialsGrant(), new \DateInterval('PT1H'));

        $server->enableGrantType(new Server\Grant\ImplicitGrant(new \DateInterval('PT1H')));

        $grant = new Server\Grant\PasswordGrant($userRepository, $refreshTokenRepository);
        $grant->setRefreshTokenTTL(new \DateInterval('P1M'));
        $server->enableGrantType($grant, new \DateInterval('PT1H'));

        $grant = new Server\Grant\RefreshTokenGrant($refreshTokenRepository);
        $grant->setRefreshTokenTTL(new \DateInterval('P1M'));
        $server->enableGrantType($grant, new \DateInterval('PT1H'));

        return $server;
    },
    Server\ResourceServer::class => function (ContainerInterface $container) {
        $config = $container->get('config')['oauth'];

        $accessTokenRepository = $container->get(Server\Repositories\AccessTokenRepositoryInterface::class);

        return new Server\ResourceServer(
            $accessTokenRepository,
            new Server\CryptKey($config['public_key_path'], null, false)
        );
    },
    Server\Middleware\ResourceServerMiddleware::class => function (ContainerInterface $container) {
        return new Server\Middleware\ResourceServerMiddleware(
            $container->get(Server\ResourceServer::class)
        );
    },
    Server\Repositories\ClientRepositoryInterface::class => function (ContainerInterface $container) {
        $config = $container->get('config')['oauth'];
        return new Infrastructure\Entity\ClientRepository($config['clients']);
    },
    Server\Repositories\ScopeRepositoryInterface::class => function () {
        return new Infrastructure\Entity\ScopeRepository();
    },
    Server\Repositories\AuthCodeRepositoryInterface::class => function (ContainerInterface $container) {
        return new Infrastructure\Entity\AuthCodeRepository(
            $container->get(EntityManagerInterface::class)
        );
    },
    Server\Repositories\AccessTokenRepositoryInterface::class => function (ContainerInterface $container) {
        return new Infrastructure\Entity\AccessTokenRepository(
            $container->get(EntityManagerInterface::class)
        );
    },
    Server\Repositories\RefreshTokenRepositoryInterface::class => function (ContainerInterface $container) {
        return new Infrastructure\Entity\RefreshTokenRepository(
            $container->get(EntityManagerInterface::class)
        );
    },
    Server\Repositories\UserRepositoryInterface::class => function (ContainerInterface $container) {
        return new Infrastructure\Entity\UserRepository(
            $container->get(EntityManagerInterface::class),
            $container->get(PasswordHasher::class)
        );
    },

    'config' => [
        'oauth' => [
            'public_key_path' => dirname(__DIR__, 2) . '/' . getenv('API_OAUTH_PUBLIC_KEY_PATH'),
            'private_key_path' => dirname(__DIR__, 2) . '/' . getenv('API_OAUTH_PRIVATE_KEY_PATH'),
            'encryption_key' => getenv('API_OAUTH_ENCRYPTION_KEY'),
            'clients' => [
                'app' => [
                    'secret'          => null,
                    'name'            => 'App',
                    'redirect_uri'    => null,
                    'is_confidential' => false,
                ],
            ],
        ],
    ],
];