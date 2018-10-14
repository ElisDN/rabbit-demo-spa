<?php

declare(strict_types=1);

namespace Api\ReadModel\Video;

use Api\Model\Video\Entity\Video\Video;

interface VideoReadRepository
{
    public function allByAuthor(string $authorId): array;

    public function find(string $authorId, string $id): ?Video;
}
