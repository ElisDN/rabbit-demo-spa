<?php

declare(strict_types=1);

namespace Api\Infrastructure\ReadModel\Video;

use Api\Model\Video\Entity\Author\Author;
use Api\ReadModel\Video\AuthorReadRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineAuthorReadRepository implements AuthorReadRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Author::class);
        $this->em = $em;
    }

    public function find(string $id): ?Author
    {
        return $this->repo->find($id);
    }
}
