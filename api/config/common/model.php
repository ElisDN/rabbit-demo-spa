<?php

declare(strict_types=1);

use Api\Infrastructure\Model\User as UserInfrastructure;
use Api\Model\User as UserModel;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    Api\Model\Flusher::class => function (ContainerInterface $container) {
        return new Api\Infrastructure\Model\Service\DoctrineFlusher(
            $container->get(EntityManagerInterface::class)
        );
    },

    UserModel\Service\PasswordHasher::class => function () {
        return new UserInfrastructure\Service\BCryptPasswordHasher();
    },

    UserModel\Entity\User\UserRepository::class => function (ContainerInterface $container) {
        return new UserInfrastructure\Entity\DoctrineUserRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    UserModel\Service\ConfirmTokenizer::class => function (ContainerInterface $container) {
        $interval = $container->get('config')['auth']['signup_confirm_interval'];
        return new UserInfrastructure\Service\RandConfirmTokenizer(new \DateInterval($interval));
    },

    'config' => [
        'auth' => [
            'signup_confirm_interval' => 'PT5M',
        ],
    ],
];
