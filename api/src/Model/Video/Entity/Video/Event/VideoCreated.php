<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Video\Event;

use Api\Model\Video\Entity\Author\AuthorId;
use Api\Model\Video\Entity\Video\VideoId;

class VideoCreated
{
    public $id;
    public $author;
    public $origin;

    public function __construct(VideoId $id, AuthorId $author, string $origin)
    {
        $this->id = $id;
        $this->author = $author;
        $this->origin = $origin;
    }
}
