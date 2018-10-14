<?php

declare(strict_types=1);

namespace Api\Http\Action\Author\Video;

use Api\Http\VideoUrl;
use Api\Model\Video\Entity\Video\File;
use Api\Model\Video\Entity\Video\Video;
use Api\ReadModel\Video\VideoReadRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class ShowAction implements RequestHandlerInterface
{
    private $videos;
    private $url;

    public function __construct(VideoReadRepository $videos, VideoUrl $url)
    {
        $this->videos = $videos;
        $this->url = $url;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $author = $request->getAttribute('oauth_user_id');
        $id = $request->getAttribute('id');

        if (!$video = $this->videos->find($author, $id)) {
            return new JsonResponse([], 404);
        }

        return new JsonResponse($this->serialize($video));
    }

    private function serialize(Video $video): array
    {
        return [
            'id' => $video->getId()->getId(),
            'name' => $video->getName(),
            'files' => array_map(function (File $file) {
                return [
                    'url' => $this->url->url($file->getPath()),
                    'format' => $file->getFormat(),
                    'size' => [
                        'width' => $file->getSize()->getWidth(),
                        'height' => $file->getSize()->getHeight(),
                    ],
                ];
            }, $video->getFiles()),
        ];
    }
}
