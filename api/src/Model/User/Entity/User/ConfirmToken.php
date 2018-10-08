<?php

declare(strict_types=1);

namespace Api\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
class ConfirmToken
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $token;
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $expires;

    public function __construct(string $token, \DateTimeImmutable $expires)
    {
        Assert::notEmpty($token);
        $this->token = $token;
        $this->expires = $expires;
    }

    public function validate(string $token, \DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($token)) {
            throw new \DomainException('Confirm token is invalid.');
        }
        if ($this->isExpiredTo($date)) {
            throw new \DomainException('Confirm token is expired.');
        }
    }

    private function isEqualTo(string $token): bool
    {
        return $this->token === $token;
    }

    private function isExpiredTo(\DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function isEmpty(): bool
    {
        return empty($this->token);
    }
}
