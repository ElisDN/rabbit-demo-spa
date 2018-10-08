<?php

declare(strict_types=1);

namespace Api\Data\Fixture;

use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\UserId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User(
            UserId::next(),
            $now = new \DateTimeImmutable(),
            new Email('user@app.dev'),
            '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // 'secret'
            new ConfirmToken('token', new \DateTimeImmutable('+1 day'))
        );

        $manager->persist($user);

        $manager->flush();
    }
}

