<?php

declare(strict_types=1);

namespace Api\Model\Video\Service\Processor;

class Video
{
    private $path;
    private $format;
    private $size;

    public function __construct(string $path, Format $format, Size $size)
    {
        $this->path = $path;
        $this->format = $format;
        $this->size = $size;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFormat(): Format
    {
        return $this->format;
    }

    public function getSize(): Size
    {
        return $this->size;
    }
}