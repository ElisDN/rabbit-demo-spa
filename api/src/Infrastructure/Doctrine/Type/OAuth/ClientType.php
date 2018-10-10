<?php

declare(strict_types=1);

namespace Api\Infrastructure\Doctrine\Type\OAuth;

use Api\Model\OAuth\Entity\ClientEntity;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ClientType extends StringType
{
    public const NAME = 'oauth_client';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof ClientEntity ? $value->getIdentifier() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!empty($value)) {
            $client = new ClientEntity($value);
            $client->setName($value);
            return $client;
        }
        return null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}