<?php

declare(strict_types=1);

namespace Api\Model\Video\Service\Processor;

class Image
{
    private $path;
    private $size;

    public function __construct(string $path, Size $size)
    {
        $this->path = $path;
        $this->size = $size;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getSize(): Size
    {
        return $this->size;
    }
}