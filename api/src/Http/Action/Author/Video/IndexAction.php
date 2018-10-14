<?php

declare(strict_types=1);

namespace Api\Http\Action\Author\Video;

use Api\Http\VideoUrl;
use Api\Model\Video\Entity\Video\Video;
use Api\ReadModel\Video\AuthorReadRepository;
use Api\ReadModel\Video\VideoReadRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class IndexAction implements RequestHandlerInterface
{
    private $authors;
    private $videos;
    private $url;

    public function __construct(AuthorReadRepository $authors, VideoReadRepository $videos, VideoUrl $url)
    {
        $this->authors = $authors;
        $this->videos = $videos;
        $this->url = $url;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$author = $this->authors->find($request->getAttribute('oauth_user_id'))) {
            return new JsonResponse([], 403);
        }

        $videos = $this->videos->allByAuthor($author->getId()->getId());

        return new JsonResponse([
            'count' => \count($videos),
            'data' => array_map([$this, 'serialize'], $videos),
        ]);
    }

    private function serialize(Video $video): array
    {
        return [
            'id' => $video->getId()->getId(),
            'name' => $video->getName(),
            'thumbnail' => [
                'url' => $this->url->url($video->getThumbnail()->getPath()),
                'width' => $video->getThumbnail()->getSize()->getWidth(),
                'height' => $video->getThumbnail()->getSize()->getHeight(),
            ],
        ];
    }
}
