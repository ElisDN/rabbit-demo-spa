<?php

declare(strict_types=1);

namespace Api\Test\Unit\Model\User\Entity\User;

use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\UserId;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ConfirmToken('token', $now->modify('+1 day'));

        $user = $this->signUp($token);

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());
        self::assertNotNull($user->getConfirmToken());

        $user->confirmSignup($token->getToken(), $now);

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());
        self::assertNull($user->getConfirmToken());
    }

    public function testInvalidToken(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ConfirmToken('token', $now->modify('+1 day'));
        $user = $this->signUp($token);

        $this->expectExceptionMessage('Confirm token is invalid.');
        $user->confirmSignup('invalid', $now);
    }

    public function testExpiredToken(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ConfirmToken('token', $now);
        $user = $this->signUp($token);

        $this->expectExceptionMessage('Confirm token is expired.');
        $user->confirmSignup($token->getToken(), $now->modify('+1 day'));
    }

    public function testAlreadyActive(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ConfirmToken('token', $now->modify('+1 day'));
        $user = $this->signUp($token);

        $user->confirmSignup($token->getToken(), $now);
        $this->expectExceptionMessage('User is already active.');
        $user->confirmSignup($token->getToken(), $now);
    }

    private function signUp(ConfirmToken $token): User
    {
        return new User(
            UserId::next(),
            new \DateTimeImmutable(),
            new Email('mail@example.com'),
            'hash',
            $token
        );
    }
}
