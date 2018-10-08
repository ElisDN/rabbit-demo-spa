<?php

declare(strict_types=1);

namespace Api\Http\Action\Auth\SignUp;

use Api\Model\User\UseCase\SignUp\Request\Command;
use Api\Model\User\UseCase\SignUp\Request\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class RequestAction implements RequestHandlerInterface
{
    private $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $command = new Command();

        $command->email = $body['email'] ?? '';
        $command->password = $body['password'] ?? '';

        $this->handler->handle($command);

        return new JsonResponse([
            'email' => $command->email,
        ], 201);
    }
}
