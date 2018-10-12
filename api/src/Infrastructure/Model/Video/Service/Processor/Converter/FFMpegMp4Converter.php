<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\Video\Service\Processor\Converter;

use Api\Model\Video\Service\Processor\Converter\Driver;
use Api\Model\Video\Service\Processor\Format;
use Api\Model\Video\Service\Processor\Size;
use Api\Model\Video\Service\Processor\Video;

class FFMpegMp4Converter implements Driver
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function canConvert(Format $from, Format $to): bool
    {
        return $to->getName() === 'mp4';
    }

    public function convert(Video $video, Format $format, Size $size): Video
    {
        $source = $this->path . '/' . $video->getPath();
        $filename = $this->createFileName($video, $size);
        $target = $this->path . '/' . $filename;

        $width = $size->getWidth();
        $height = $size->getHeight();

        exec(
            "ffmpeg -i {$source} -acodec aac -strict experimental -ac 2 -vcodec libx264 -f mp4 -crf 22 " .
            "-vf 'scale={$width}:{$height}:force_original_aspect_ratio=decrease,pad={$width}:{$height}:x=({$width}-iw)/2:y=({$height}-ih)/2:color=black'" .
            " {$target}", $output, $return
        );

        if ($return !== 0) {
            throw new \RuntimeException('Unable to convert ' . $video->getPath() . ' to ' . $format->getName());
        }

        return new Video($filename, $format, $size);
    }

    private function createFileName(Video $video, Size $size): string
    {
        return pathinfo($video->getPath(), PATHINFO_FILENAME) . '_' . $size->getWidth() . 'x' . $size->getHeight() . '.mp4';
    }
}
