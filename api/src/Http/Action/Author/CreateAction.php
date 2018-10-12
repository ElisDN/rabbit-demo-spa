<?php

declare(strict_types=1);

namespace Api\Http\Action\Author;

use Api\Http\ValidationException;
use Api\Http\Validator\Validator;
use Api\Model\Video\UseCase\Author\Create\Command;
use Api\Model\Video\UseCase\Author\Create\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class CreateAction implements RequestHandlerInterface
{
    private $handler;
    private $validator;

    public function __construct(Handler $handler, Validator $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = $this->deserialize($request);

        if ($errors = $this->validator->validate($command)) {
            throw new ValidationException($errors);
        }

        $this->handler->handle($command);

        return new JsonResponse([
            'id' => $command->id,
            'name' => $command->name,
        ], 201);
    }

    private function deserialize(ServerRequestInterface $request): Command
    {
        $body = $request->getParsedBody();

        $command = new Command();

        $command->id = $request->getAttribute('oauth_user_id');
        $command->name = $body['name'] ?? '';

        return $command;
    }
}
