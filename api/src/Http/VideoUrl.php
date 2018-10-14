<?php

declare(strict_types=1);

namespace Api\Http;

class VideoUrl
{
    private $base;

    public function __construct(string $base)
    {
        $this->base = $base;
    }

    public function url(string $path): string
    {
        return $this->base . '/' . $path;
    }
}
