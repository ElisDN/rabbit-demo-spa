<?php

declare(strict_types=1);

namespace Api\Model\User\Entity\User;

use Api\Model\AggregateRoot;
use Api\Model\EventTrait;
use Api\Model\User\Entity\User\Event\UserConfirmed;
use Api\Model\User\Entity\User\Event\UserCreated;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"})
 * })
 */
class User implements AggregateRoot
{
    use EventTrait;

    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    /**
     * @ORM\Column(type="user_user_id")
     * @ORM\Id
     */
    private $id;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;
    /**
     * @ORM\Column(type="user_user_email")
     */
    private $email;
    /**
     * @ORM\Column(type="string", name="password_hash")
     */
    private $passwordHash;
    /**
     * @var ConfirmToken
     * @ORM\Embedded(class="ConfirmToken", columnPrefix="confirm_token_")
     */
    private $confirmToken;
    /**
     * @ORM\Column(type="string", length=16)
     */
    private $status;

    public function __construct(
        UserId $id,
        \DateTimeImmutable $date,
        Email $email,
        string $hash,
        ConfirmToken $confirmToken
    )
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $confirmToken;
        $this->status = self::STATUS_WAIT;
        $this->recordEvent(new UserCreated($this->id, $this->email, $this->confirmToken));
    }

    public function confirmSignup(string $token, \DateTimeImmutable $date): void
    {
        if ($this->isActive()) {
            throw new \DomainException('User is already active.');
        }
        $this->confirmToken->validate($token, $date);
        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
        $this->recordEvent(new UserConfirmed($this->id));
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?ConfirmToken
    {
        return $this->confirmToken;
    }

    /**
     * @ORM\PostLoad()
     */
    public function checkEmbeds(): void
    {
        if ($this->confirmToken->isEmpty()) {
            $this->confirmToken = null;
        }
    }
}
