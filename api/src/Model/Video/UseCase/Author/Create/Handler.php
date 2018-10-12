<?php

declare(strict_types=1);

namespace Api\Model\Video\UseCase\Author\Create;

use Api\Model\Flusher;
use Api\Model\Video\Entity\Author\Author;
use Api\Model\Video\Entity\Author\AuthorId;
use Api\Model\Video\Entity\Author\AuthorRepository;

class Handler
{
    private $authors;
    private $flusher;

    public function __construct(AuthorRepository $authors, Flusher $flusher)
    {
        $this->authors = $authors;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $id = new AuthorId($command->id);

        if ($this->authors->hasById($id)) {
            throw new \DomainException('Author already exists.');
        }

        $author = new Author(
            $id,
            $command->name
        );

        $this->authors->add($author);

        $this->flusher->flush($author);
    }
}