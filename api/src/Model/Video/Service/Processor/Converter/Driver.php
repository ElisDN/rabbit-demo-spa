<?php

declare(strict_types=1);

namespace Api\Model\Video\Service\Processor\Converter;

use Api\Model\Video\Service\Processor\Format;
use Api\Model\Video\Service\Processor\Size;
use Api\Model\Video\Service\Processor\Video;

interface Driver
{
    public function canConvert(Format $from, Format $to): bool;

    public function convert(Video $video, Format $format, Size $size): Video;
}