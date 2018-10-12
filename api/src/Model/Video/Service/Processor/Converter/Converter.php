<?php

declare(strict_types=1);

namespace Api\Model\Video\Service\Processor\Converter;

use Api\Model\Video\Service\Processor\Format;
use Api\Model\Video\Service\Processor\Size;
use Api\Model\Video\Service\Processor\Video;

class Converter
{
    /**
     * @var Driver[]
     */
    private $drivers;

    public function __construct(array $drivers)
    {
        $this->drivers = $drivers;
    }

    public function convert(Video $video, Format $format, Size $size): Video
    {
        return $this
            ->resolveDriver($video->getFormat(), $format)
            ->convert($video, $format, $size);
    }

    private function resolveDriver(Format $from, Format $to): Driver
    {
        foreach ($this->drivers as $driver) {
            if ($driver->canConvert($from, $to)) {
                return $driver;
            }
        }

        throw new \RuntimeException(sprintf(
            'Unable to find a driver from %s to $s',
            $from->getName(),
            $to->getName()
        ));
    }
}