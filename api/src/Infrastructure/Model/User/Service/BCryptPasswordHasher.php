<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\User\Service;

use Api\Model\User\Service\PasswordHasher;

class BCryptPasswordHasher implements PasswordHasher
{
    private $cost;

    public function __construct(int $cost = 12)
    {
        $this->cost = $cost;
    }

    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $this->cost]);
        if ($hash === false) {
            throw new \RuntimeException('Unable to generate hash.');
        }
        return $hash;
    }

    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
