<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\Video\Entity;

use Api\Model\EntityNotFoundException;
use Api\Model\Video\Entity\Video\Video;
use Api\Model\Video\Entity\Video\VideoId;
use Api\Model\Video\Entity\Video\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineVideoRepository implements VideoRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Video::class);
        $this->em = $em;
    }

    public function get(VideoId $id): Video
    {
        /** @var Video $video */
        if (!$video = $this->repo->find($id->getId())) {
            throw new EntityNotFoundException('Video is not found.');
        }
        return $video;
    }

    public function add(Video $video): void
    {
        $this->em->persist($video);
    }

    public function remove(Video $video): void
    {
        $this->em->remove($video);
    }
}
