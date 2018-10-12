<?php

declare(strict_types=1);

namespace Api\Infrastructure\Doctrine\Type\Video;

use Api\Model\Video\Entity\Video\VideoId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class VideoIdType extends GuidType
{
    public const NAME = 'video_video_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof VideoId ? $value->getId() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new VideoId($value) : null;
    }

    public function getName(): string {
        return self::NAME;
    }
}
