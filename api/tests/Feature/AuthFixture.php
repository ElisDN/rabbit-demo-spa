<?php

declare(strict_types=1);

namespace Api\Test\Feature;

use Api\Model\OAuth\Entity\AccessTokenEntity;
use Api\Model\OAuth\Entity\ClientEntity;
use Api\Model\OAuth\Entity\ScopeEntity;
use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\User;
use Api\Test\Builder\User\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class AuthFixture extends AbstractFixture
{
    private $user;
    private $token;

    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withDate($now = new \DateTimeImmutable())
            ->withEmail(new Email('test@example.com'))
            ->withConfirmToken(new ConfirmToken($token = 'token', $now->modify('+1 day')))
            ->build();

        $user->confirmSignup($token, new \DateTimeImmutable());

        $manager->persist($user);

        $this->user = $user;

        $token = new AccessTokenEntity();
        $token->setIdentifier(bin2hex(random_bytes(40)));
        $token->setUserIdentifier($user->getId()->getId());
        $token->setExpiryDateTime(new \DateTime('+1 hour'));
        $token->setClient(new ClientEntity('app'));
        $token->addScope(new ScopeEntity('common'));

        $manager->persist($token);

        $manager->flush();

        $this->addReference('user', $user);

        $this->token = (string)$token->convertToJWT(CryptKeyHelper::get());
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
        ];
    }
}
