<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\Video\Service\Uploader;

use Api\Model\Video\Service\Uploader;
use Psr\Http\Message\UploadedFileInterface;
use Ramsey\Uuid\Uuid;

class LocalUploader implements Uploader
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function upload(UploadedFileInterface $file): string
    {
        $filename = $this->generateName($file);
        $file->moveTo($this->path . '/' . $filename);
        return $filename;
    }

    private function generateName(UploadedFileInterface $file): string
    {
        $ext = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION) ?: 'jpg';
        $name = Uuid::uuid4()->toString();
        return $name . '.' . $ext;
    }
}