<?php

declare(strict_types=1);

namespace Api\Test\Feature\Author\Create;

use Api\Model\User\Entity\User\User;
use Api\Model\Video\Entity\Author\Author;
use Api\Model\Video\Entity\Author\AuthorId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class Fixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference('user');

        $author = new Author(
            new AuthorId($user->getId()->getId()),
            'Test Author'
        );

        $manager->persist($author);
        $manager->flush();
    }
}
