<?php

declare(strict_types=1);

namespace Api\ReadModel\Video;

use Api\Model\Video\Entity\Author\Author;

interface AuthorReadRepository
{
    public function find(string $id): ?Author;
}
