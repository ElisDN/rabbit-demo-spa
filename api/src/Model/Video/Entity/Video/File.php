<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Video;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="video_video_files")
 */
class File
{
    /**
     * Surrogate key
     * @ORM\Column(type="guid")
     * @ORM\Id
     */
    private $id;
    /**
     * @var Video
     * @ORM\ManyToOne(targetEntity="Video")
     * @ORM\JoinColumn(name="video_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $video;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $path;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $format;
    /**
     * @var Size
     * @ORM\Embedded(class="Size")
     */
    private $size;

    public function __construct(Video $video, string $path, string $format, Size $size)
    {
        $this->id = Uuid::uuid4()->toString();
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