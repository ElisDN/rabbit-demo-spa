<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Video;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Thumbnail
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $path;
    /**
     * @var Size
     * @ORM\Embedded(class="Size")
     */
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