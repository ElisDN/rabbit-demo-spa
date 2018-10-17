<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Video\Event;

use Api\Model\Video\Entity\Author\AuthorId;
use Api\Model\Video\Entity\Video\File;
use Api\Model\Video\Entity\Video\VideoId;

class VideoFileAdded
{
    public $id;
    public $author;
    public $file;

    public function __construct(VideoId $id, AuthorId $author, File $file)
    {
        $this->id = $id;
        $this->author = $author;
        $this->file = $file;
    }
}
