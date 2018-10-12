<?php

declare(strict_types=1);

namespace Api\Model\Video\Service\Processor;

interface FormatDetector
{
    public function detect(string $path): Video;
}