<?php

declare(strict_types=1);

use Api\Infrastructure;
use Api\Infrastructure\Model\User as UserInfrastructure;
use Api\Infrastructure\Model\Video as VideoInfrastructure;
use Api\Model\User as UserModel;
use Api\Model\Video as VideoModel;
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

    VideoModel\UseCase\Author\Create\Handler::class => function (ContainerInterface $container) {
        return new VideoModel\UseCase\Author\Create\Handler(
            $container->get(VideoModel\Entity\Author\AuthorRepository::class),
            $container->get(Api\Model\Flusher::class)
        );
    },

    ReadModel\Video\AuthorReadRepository::class => function (ContainerInterface $container) {
        return new Infrastructure\ReadModel\Video\DoctrineAuthorReadRepository(
            $container->get(\Doctrine\ORM\EntityManagerInterface::class)
        );
    },

    ReadModel\Video\VideoReadRepository::class => function (ContainerInterface $container) {
        return new Infrastructure\ReadModel\Video\DoctrineVideoReadRepository(
            $container->get(\Doctrine\ORM\EntityManagerInterface::class)
        );
    },

    VideoModel\Entity\Author\AuthorRepository::class => function (ContainerInterface $container) {
        return new VideoInfrastructure\Entity\DoctrineAuthorRepository(
            $container->get(\Doctrine\ORM\EntityManagerInterface::class)
        );
    },

    VideoModel\Entity\Video\VideoRepository::class => function (ContainerInterface $container) {
        return new VideoInfrastructure\Entity\DoctrineVideoRepository(
            $container->get(\Doctrine\ORM\EntityManagerInterface::class)
        );
    },

    VideoModel\UseCase\Video\Create\Handler::class => function (ContainerInterface $container) {
        return new VideoModel\UseCase\Video\Create\Handler(
            $container->get(VideoModel\Entity\Video\VideoRepository::class),
            $container->get(VideoModel\Entity\Author\AuthorRepository::class),
            $container->get(VideoModel\Service\Uploader::class),
            $container->get(VideoModel\Service\Processor\FormatDetector::class),
            $container->get(VideoModel\Service\Processor\Converter\Converter::class),
            $container->get(VideoModel\Service\Processor\Thumbnailer\Thumbnailer::class),
            $container->get(Api\Model\Flusher::class),
            $container->get(VideoModel\UseCase\Video\Create\Preferences::class)
        );
    },

    'config' => [
        'auth' => [
            'signup_confirm_interval' => 'PT5M',
        ],
    ],
];
