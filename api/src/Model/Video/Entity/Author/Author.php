<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Author;

use Api\Model\AggregateRoot;
use Api\Model\EventTrait;
use Api\Model\Video\Entity\Author\Event\AuthorCreated;

class Author implements AggregateRoot
{
    use EventTrait;

    /**
     * @var AuthorId
     */
    private $id;
    /**
     * @var string
     */
    private $name;

    public function __construct(AuthorId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->recordEvent(new AuthorCreated($this->id));
    }

    public function getId(): AuthorId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}