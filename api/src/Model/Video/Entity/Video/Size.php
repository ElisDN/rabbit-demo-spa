<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Video;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Size
{
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $width;
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}