<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Author;

interface AuthorRepository
{
    public function hasById(AuthorId $id): bool;

    public function get(AuthorId $id): Author;

    public function add(Author $author): void;
}