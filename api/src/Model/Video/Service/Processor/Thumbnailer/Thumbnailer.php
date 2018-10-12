<?php

declare(strict_types=1);

namespace Api\Model\Video\Service\Processor\Thumbnailer;

use Api\Model\Video\Service\Processor\Format;
use Api\Model\Video\Service\Processor\Image;
use Api\Model\Video\Service\Processor\Size;
use Api\Model\Video\Service\Processor\Video;

class Thumbnailer
{
    /**
     * @var Driver[]
     */
    private $drivers;

    public function __construct(array $drivers)
    {
        $this->drivers = $drivers;
    }

    public function thumbnail(Video $video, Size $size): Image
    {
        return $this
            ->resolveDriver($video->getFormat())
            ->thumbnail($video, $size);
    }

    private function resolveDriver(Format $from): Driver
    {
        foreach ($this->drivers as $driver) {
            if ($driver->canThumbnail($from)) {
                return $driver;
            }
        }

        throw new \RuntimeException(sprintf(
            'Unable to find a thumbnailer from %s',
            $from->getName()
        ));
    }
}