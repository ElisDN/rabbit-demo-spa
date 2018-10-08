<?php

declare(strict_types=1);

namespace Api\Model\User\Entity\User;

interface UserRepository
{
    public function hasByEmail(Email $email): bool;

    public function getByEmail(Email $email): User;

    public function add(User $user): void;
}
