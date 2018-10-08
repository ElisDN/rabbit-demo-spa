<?php

declare(strict_types=1);

namespace Api\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class ConfirmToken
{
    private $token;
    private $expires;

    public function __construct(string $token, \DateTimeImmutable $expires)
    {
        Assert::notEmpty($token);
        $this->token = $token;
        $this->expires = $expires;
    }

    public function isExpiredTo(\DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }
}
