<?php

declare(strict_types=1);

namespace Api\ReadModel\User;

use Api\Model\User\Entity\User\User;

interface UserReadRepository
{
    public function find(string $id): ?User;
}
