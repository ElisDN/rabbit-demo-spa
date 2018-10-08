<?php

declare(strict_types=1);

namespace Api\Test\Unit\Model\User\Entity\User;

use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;
use Api\Test\Builder\User\UserBuilder;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ConfirmToken('token', $now->modify('+1 day'));

        $user = $this->signUp($now, $token);

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
        $user = $this->signUp($now, $token);

        $this->expectExceptionMessage('Confirm token is invalid.');
        $user->confirmSignup('invalid', $now);
    }

    public function testExpiredToken(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ConfirmToken('token', $now);
        $user = $this->signUp($now, $token);

        $this->expectExceptionMessage('Confirm token is expired.');
        $user->confirmSignup($token->getToken(), $now->modify('+1 day'));
    }

    public function testAlreadyActive(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ConfirmToken('token', $now->modify('+1 day'));
        $user = $this->signUp($now, $token);

        $user->confirmSignup($token->getToken(), $now);
        $this->expectExceptionMessage('User is already active.');
        $user->confirmSignup($token->getToken(), $now);
    }

    private function signUp(\DateTimeImmutable $date, ConfirmToken $token): User
    {
        return (new UserBuilder())
            ->withDate($date)
            ->withConfirmToken($token)
            ->build();
    }
}
