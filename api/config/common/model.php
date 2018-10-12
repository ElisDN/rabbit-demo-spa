<?php

declare(strict_types=1);

use Api\Infrastructure;
use Api\Infrastructure\Model\User as UserInfrastructure;
use Api\Model\User as UserModel;
use Api\ReadModel;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    Api\Model\Flusher::class => function (ContainerInterface $container) {
        return new Api\Infrastructure\Model\Service\DoctrineFlusher(
            $container->get(EntityManagerInterface::class),
            $container->get(Api\Model\EventDispatcher::class)
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

    UserModel\Entity\User\UserRepository::class => function (ContainerInterface $container) {
        return new UserInfrastructure\Entity\DoctrineUserRepository(
            $container->get(\Doctrine\ORM\EntityManagerInterface::class)
        );
    },

    UserModel\UseCase\SignUp\Request\Handler::class => function (ContainerInterface $container) {
        return new UserModel\UseCase\SignUp\Request\Handler(
            $container->get(UserModel\Entity\User\UserRepository::class),
            $container->get(UserModel\Service\PasswordHasher::class),
            $container->get(UserModel\Service\ConfirmTokenizer::class),
            $container->get(Api\Model\Flusher::class)
        );
    },

    UserModel\UseCase\SignUp\Confirm\Handler::class => function (ContainerInterface $container) {
        return new UserModel\UseCase\SignUp\Confirm\Handler(
            $container->get(UserModel\Entity\User\UserRepository::class),
            $container->get(Api\Model\Flusher::class)
        );
    },

    ReadModel\User\UserReadRepository::class => function (ContainerInterface $container) {
        return new Infrastructure\ReadModel\User\DoctrineUserReadRepository(
            $container->get(\Doctrine\ORM\EntityManagerInterface::class)
        );
    },

    'config' => [
        'auth' => [
            'signup_confirm_interval' => 'PT5M',
        ],
    ],
];
