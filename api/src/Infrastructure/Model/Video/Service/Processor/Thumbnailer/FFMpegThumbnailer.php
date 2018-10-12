<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\Video\Service\Processor\Thumbnailer;

use Api\Model\Video\Service\Processor\Image;
use Api\Model\Video\Service\Processor\Thumbnailer\Driver;
use Api\Model\Video\Service\Processor\Format;
use Api\Model\Video\Service\Processor\Size;
use Api\Model\Video\Service\Processor\Video;

class FFMpegThumbnailer implements Driver
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function canThumbnail(Format $from): bool
    {
        return true;
    }

    public function thumbnail(Video $video, Size $size): Image
    {
        $source = $this->path . '/' . $video->getPath();
        $filename = $this->createFileName($video, $size);
        $target = $this->path . '/' . $filename;

        $width = $size->getWidth();
        $height = $size->getHeight();

        exec("ffmpeg -i {$source} -ss 00:00:01.000 -vframes 1 -s {$width}x{$height} {$target}", $output, $return);

        if ($return !== 0) {
            throw new \RuntimeException('Unable to make thumbnail for ' . $video->getPath());
        }

        return new Image($filename, $size);
    }

    private function createFileName(Video $video, Size $size): string
    {
        return pathinfo($video->getPath(), PATHINFO_FILENAME) . '_' . $size->getWidth() . 'x' . $size->getHeight() . '.png';
    }
}
