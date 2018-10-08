<?php

declare(strict_types=1);

namespace Api\Test\Feature\Auth\SignUp;

use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\UserId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User(
            UserId::next(),
            $now = new \DateTimeImmutable(),
            new Email('test@example.com'),
            'password_hash',
            new ConfirmToken($token = 'token', new \DateTimeImmutable('+1 day'))
        );

        $user->confirmSignup($token, $now);

        $manager->persist($user);
        $manager->flush();
    }
}
