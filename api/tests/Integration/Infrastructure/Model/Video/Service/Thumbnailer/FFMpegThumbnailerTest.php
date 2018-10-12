<?php

declare(strict_types=1);

namespace Api\Test\Integration\Infrastructure\Model\Video\Service\Thumbnailer;

use Api\Infrastructure\Model\Video\Service\Processor\Thumbnailer\FFMpegThumbnailer;
use Api\Model\Video\Service\Processor\Format;
use Api\Model\Video\Service\Processor\Size;
use Api\Model\Video\Service\Processor\Video;
use PHPUnit\Framework\TestCase;

class FFMpegThumbnailerTest extends TestCase
{
    private $path;
    /**
     * @var FFMpegThumbnailer
     */
    private $thumbnailer;

    protected function setUp(): void
    {
        parent::setUp();
        $path = realpath('var/test');
        $this->initDemoFiles($path);
        $this->path = $path;
        $this->thumbnailer =  new FFMpegThumbnailer($this->path);
    }

    public function testMp4(): void
    {
        $video = new Video(
            'video.mp4',
            new Format('mp4'),
            new Size(560, 320)
        );

        $thumb = $this->thumbnailer->thumbnail($video, new Size(320, 240));

        self::assertEquals('video_320x240.png', $thumb->getPath());
        self::assertEquals(320, $thumb->getSize()->getWidth());
        self::assertEquals(240, $thumb->getSize()->getHeight());
        self::assertFileExists($this->path . '/video_320x240.png');
    }

    protected function initDemoFiles(string $path): void
    {
        if (file_exists($path . '/video.mp4')) {
            unlink($path . '/video.mp4');
        }

        copy(\dirname(__DIR__) . '/data/video.mp4', $path . '/video.mp4');

        if (file_exists($path . '/video_320x240.png')) {
            unlink($path . '/video_320x240.png');
        }
    }
}
