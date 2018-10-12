<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Author\Event;

use Api\Model\Video\Entity\Author\AuthorId;

class AuthorCreated
{
    public $id;

    public function __construct(AuthorId $id)
    {
        $this->id = $id;
    }
}
