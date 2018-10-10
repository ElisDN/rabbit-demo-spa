<?php

declare(strict_types=1);

namespace Api\Test\Builder\User;

use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\UserId;

class UserBuilder
{
    private $id;
    private $date;
    private $email;
    private $hash;
    private $confirmToken;

    public function __construct()
    {
        $this->id = UserId::next();
        $this->date = new \DateTimeImmutable();
        $this->email = new Email('mail@example.com');
        $this->hash = 'hash';
        $this->confirmToken = new ConfirmToken('token', new \DateTimeImmutable('+1 day'));
    }

    public function withId(UserId $id): self
    {
        $clone = clone $this;
        $clone->id = $id;
        return $clone;
    }

    public function withDate(\DateTimeImmutable $date): self
    {
        $clone = clone $this;
        $clone->date = $date;
        return $clone;
    }

    public function withEmail(Email $email): self
    {
        $clone = clone $this;
        $clone->email = $email;
        return $clone;
    }

    public function withPasswordHash(string $hash): self
    {
        $clone = clone $this;
        $clone->hash = $hash;
        return $clone;
    }

    public function withConfirmToken(ConfirmToken $confirmToken): self
    {
        $clone = clone $this;
        $clone->confirmToken = $confirmToken;
        return $clone;
    }

    public function build(): User
    {
        return new User(
            $this->id,
            $this->date,
            $this->email,
            $this->hash,
            $this->confirmToken
        );
    }
}
