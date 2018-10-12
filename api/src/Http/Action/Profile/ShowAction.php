<?php

declare(strict_types=1);

namespace Api\Http\Action\Profile;

use Api\ReadModel\User\UserReadRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class ShowAction implements RequestHandlerInterface
{
    private $users;

    public function __construct(UserReadRepository $users)
    {
        $this->users = $users;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$user = $this->users->find($request->getAttribute('oauth_user_id'))) {
            return new JsonResponse([], 404);
        }

        return new JsonResponse([
            'id' => $user->getId()->getId(),
            'email' => $user->getEmail()->getEmail(),
        ]);
    }
}
