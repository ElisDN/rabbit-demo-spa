<?php

declare(strict_types=1);

namespace Api\Http\Action\Author;

use Api\ReadModel\Video\AuthorReadRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class ShowAction implements RequestHandlerInterface
{
    private $authors;

    public function __construct(AuthorReadRepository $authors)
    {
        $this->authors = $authors;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$author = $this->authors->find($request->getAttribute('oauth_user_id'))) {
            return new JsonResponse([], 204);
        }

        return new JsonResponse([
            'id' => $author->getId()->getId(),
            'name' => $author->getName(),
        ]);
    }
}
