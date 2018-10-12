<?php

declare(strict_types=1);

namespace Api\Model\Video\Service\Processor\Thumbnailer;

use Api\Model\Video\Service\Processor\Format;
use Api\Model\Video\Service\Processor\Image;
use Api\Model\Video\Service\Processor\Size;
use Api\Model\Video\Service\Processor\Video;

interface Driver
{
    public function canThumbnail(Format $from): bool;

    public function thumbnail(Video $video, Size $size): Image;
}