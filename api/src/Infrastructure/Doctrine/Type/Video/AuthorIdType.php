<?php

declare(strict_types=1);

namespace Api\Infrastructure\Doctrine\Type\Video;

use Api\Model\Video\Entity\Author\AuthorId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class AuthorIdType extends GuidType
{
    public const NAME = 'video_author_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof AuthorId ? $value->getId() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new AuthorId($value) : null;
    }

    public function getName(): string {
        return self::NAME;
    }
}
