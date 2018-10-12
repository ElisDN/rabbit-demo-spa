<?php

declare(strict_types=1);

namespace Api\Model\Video\Service;

use Psr\Http\Message\UploadedFileInterface;

interface Uploader
{
    public function upload(UploadedFileInterface $file): string;
}