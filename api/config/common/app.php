<?php

declare(strict_types=1);

use Api\Http\Action;
use Api\Http\Middleware;
use Api\Http\Validator\Validator;
use Api\Http\VideoUrl;
use Api\Model;
use Api\ReadModel;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

return [
    ValidatorInterface::class => function () {
        AnnotationRegistry::registerLoader('class_exists');
        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    },

    Validator::class => function (ContainerInterface $container) {
        return new Validator(
            $container->get(ValidatorInterface::class)
        );
    },

    Middleware\BodyParamsMiddleware::class => function () {
        return new Middleware\BodyParamsMiddleware();
    },

    Middleware\DomainExceptionMiddleware::class => function () {
        return new Middleware\DomainExceptionMiddleware();
    },

    Middleware\ValidationExceptionMiddleware::class => function () {
        return new Middleware\ValidationExceptionMiddleware();
    },

    Action\HomeAction::class => function () {
        return new Action\HomeAction();
    },

    Action\Auth\SignUp\RequestAction::class => function (ContainerInterface $container) {
        return new Action\Auth\SignUp\RequestAction(
            $container->get(Model\User\UseCase\SignUp\Request\Handler::class),
            $container->get(Validator::class)
        );
    },

    Action\Auth\SignUp\ConfirmAction::class => function (ContainerInterface $container) {
        return new Action\Auth\SignUp\ConfirmAction(
            $container->get(Model\User\UseCase\SignUp\Confirm\Handler::class),
            $container->get(Validator::class)
        );
    },

    Action\Auth\OAuthAction::class => function (ContainerInterface $container) {
        return new Action\Auth\OAuthAction(
            $container->get(\League\OAuth2\Server\AuthorizationServer::class),
            $container->get(LoggerInterface::class)
        );
    },

    Action\Profile\ShowAction::class => function (ContainerInterface $container) {
        return new Action\Profile\ShowAction(
            $container->get(ReadModel\User\UserReadRepository::class)
        );
    },

    Action\Author\ShowAction::class => function (ContainerInterface $container) {
        return new Action\Author\ShowAction(
            $container->get(ReadModel\Video\AuthorReadRepository::class)
        );
    },

    Action\Author\CreateAction::class => function (ContainerInterface $container) {
        return new Action\Author\CreateAction(
            $container->get(Model\Video\UseCase\Author\Create\Handler::class),
            $container->get(Validator::class)
        );
    },

    Action\Author\Video\IndexAction::class => function (ContainerInterface $container) {
        return new Action\Author\Video\IndexAction(
            $container->get(ReadModel\Video\AuthorReadRepository::class),
            $container->get(ReadModel\Video\VideoReadRepository::class),
            $container->get(VideoUrl::class)
        );
    },

    Action\Author\Video\CreateAction::class => function (ContainerInterface $container) {
        return new Action\Author\Video\CreateAction(
            $container->get(Model\Video\UseCase\Video\Create\Handler::class),
            $container->get(Validator::class)
        );
    },

    Action\Author\Video\ShowAction::class => function (ContainerInterface $container) {
        return new Action\Author\Video\ShowAction(
            $container->get(ReadModel\Video\VideoReadRepository::class),
            $container->get(VideoUrl::class)
        );
    },
];