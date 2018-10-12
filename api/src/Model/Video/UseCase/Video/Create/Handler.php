<?php

declare(strict_types=1);

namespace Api\Model\Video\UseCase\Video\Create;

use Api\Model\Flusher;
use Api\Model\Video\Entity\Author\AuthorId;
use Api\Model\Video\Entity\Author\AuthorRepository;
use Api\Model\Video\Entity\Video\Size;
use Api\Model\Video\Entity\Video\Thumbnail;
use Api\Model\Video\Entity\Video\Video;
use Api\Model\Video\Entity\Video\VideoId;
use Api\Model\Video\Entity\Video\VideoRepository;
use Api\Model\Video\Service\Processor\Converter\Converter;
use Api\Model\Video\Service\Processor\FormatDetector;
use Api\Model\Video\Service\Processor\Thumbnailer\Thumbnailer;
use Api\Model\Video\Service\Uploader;

class Handler
{
    private $videos;
    private $authors;
    private $uploader;
    private $detector;
    private $converter;
    private $thumbnailer;
    private $flusher;
    private $preferences;

    public function __construct(
        VideoRepository $videos,
        AuthorRepository $authors,
        Uploader $uploader,
        FormatDetector $detector,
        Converter $converter,
        Thumbnailer $thumbnailer,
        Flusher $flusher,
        Preferences $preferences
    )
    {
        $this->videos = $videos;
        $this->authors = $authors;
        $this->uploader = $uploader;
        $this->detector = $detector;
        $this->converter = $converter;
        $this->thumbnailer = $thumbnailer;
        $this->flusher = $flusher;
        $this->preferences = $preferences;
    }

    public function handle(Command $command): VideoId
    {
        $author = $this->authors->get(new AuthorId($command->author));

        $path = $this->uploader->upload($command->file);

        $video = new Video(
            $id = VideoId::next(),
            $author,
            new \DateTimeImmutable(),
            'New Video',
            $path
        );
        
        $detected = $this->detector->detect($path);

        $thumb = $this->thumbnailer->thumbnail($detected, $this->preferences->thumbnailSize);

        $video->setThumbnail(
            new Thumbnail(
                $thumb->getPath(),
                new Size(
                    $thumb->getSize()->getWidth(),
                    $thumb->getSize()->getHeight()
                )
            )
        );

        $sizes = [];

        foreach ($this->preferences->videoSizes as $size) {
            if ($size->lessOrEqual($detected->getSize())) {
                $sizes[] = $size;
            }
        }

        if ($detected->getSize()->lessThan($this->preferences->videoSizes[0])) {
            $sizes[] = $this->preferences->videoSizes[0];
        }

        foreach ($this->preferences->videoFormats as $format) {
            foreach ($sizes as $size) {
                $processed = $this->converter->convert($detected, $format, $size);
                $video->addFile(
                    $processed->getPath(),
                    $processed->getFormat()->getName(),
                    new Size(
                        $processed->getSize()->getWidth(),
                        $processed->getSize()->getHeight()
                    )
                );
            }
        }

        $this->videos->add($video);

        $this->flusher->flush($video);

        return $id;
    }
}