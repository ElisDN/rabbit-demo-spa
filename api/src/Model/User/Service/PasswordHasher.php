<?php

declare(strict_types=1);

namespace Api\Model\User\Service;

interface PasswordHasher
{
    public function hash(string $password): string;

    public function validate(string $password, string $hash): bool;
}
