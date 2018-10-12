<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\Video\Service\Processor\FormatDetector;

use Api\Model\Video\Service\Processor\Format;
use Api\Model\Video\Service\Processor\FormatDetector;
use Api\Model\Video\Service\Processor\Size;
use Api\Model\Video\Service\Processor\Video;

class FFProbeFormatDetector implements FormatDetector
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function detect(string $path): Video
    {
        $source = $this->path . '/' . $path;

        return new Video(
            $path,
            $this->parseFormat($source),
            $this->parseSize($source)
        );
    }

    private function parseFormat(string $source): Format
    {
        return new Format(mb_strtolower(pathinfo($source, PATHINFO_EXTENSION)));
    }

    private function parseSize(string $source): Size
    {
        exec("ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=s=x:p=0 {$source}", $output, $return);

        if ($return !== 0) {
            throw new \RuntimeException('Unable to get video dimensions for ' . $output);
        }

        if (!preg_match('#^(\d+)x(\d+)$#', $output[0], $matches)) {
            throw new \RuntimeException('Incorrect dimensions for ' . $source);
        }

        return new Size((int)$matches[1], (int)$matches[2]);
    }
}
