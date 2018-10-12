<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Video\Event;

use Api\Model\Video\Entity\Author\AuthorId;
use Api\Model\Video\Entity\Video\VideoId;

class VideoPublished
{
    public $id;
    public $author;

    public function __construct(VideoId $id, AuthorId $author)
    {
        $this->id = $id;
        $this->author = $author;
    }
}
