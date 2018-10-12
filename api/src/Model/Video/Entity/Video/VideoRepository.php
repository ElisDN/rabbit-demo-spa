<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Video;

interface VideoRepository
{
    public function get(VideoId $id): Video;

    public function add(Video $video):  void;

    public function remove(Video $video):  void;
}