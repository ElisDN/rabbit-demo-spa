<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Video;

class File
{
    /**
     * @var Video
     */
    private $video;
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $format;
    /**
     * @var Size
     */
    private $size;

    public function __construct(Video $video, string $path, string $format, Size $size)
    {
        $this->video = $video;
        $this->path = $path;
        $this->format = $format;
        $this->size = $size;
    }

    public function getVideo(): Video
    {
        return $this->video;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getSize(): Size
    {
        return $this->size;
    }
}