<?php

declare(strict_types=1);

namespace Api\Test\Integration\Infrastructure\Model\Video\Service\Converter;

use Api\Infrastructure\Model\Video\Service\Processor\Converter\FFMpegMp4Converter;
use Api\Model\Video\Service\Processor\Format;
use Api\Model\Video\Service\Processor\Size;
use Api\Model\Video\Service\Processor\Video;
use PHPUnit\Framework\TestCase;

class FFMpegMp4ConverterTest extends TestCase
{
    private $path;
    /**
     * @var FFMpegMp4Converter
     */
    private $converter;

    protected function setUp(): void
    {
        parent::setUp();
        $path = realpath('var/test');
        $this->initDemoFiles($path);
        $this->path = $path;
        $this->converter =  new FFMpegMp4Converter($this->path);
    }

    public function testCan(): void
    {
        self::assertTrue($this->converter->canConvert(new Format('3gp'), new Format('mp4')));
        self::assertFalse($this->converter->canConvert(new Format('3gp'), new Format('webm')));
    }

    public function testConvert(): void
    {
        $video = new Video(
            'video.3gp',
            new Format('3gp'),
            new Size(352, 288)
        );

        $thumb = $this->converter->convert($video, new Format('mp4'), new Size(320, 240));

        self::assertEquals('video_320x240.mp4', $thumb->getPath());
        self::assertEquals(320, $thumb->getSize()->getWidth());
        self::assertEquals(240, $thumb->getSize()->getHeight());
        self::assertFileExists($this->path . '/video_320x240.mp4');
    }

    private function initDemoFiles(string $path): void
    {
        if (file_exists($path . '/video.3gp')) {
            unlink($path . '/video.3gp');
        }

        copy(\dirname(__DIR__) . '/data/video.3gp', $path . '/video.3gp');

        if (file_exists($path . '/video_320x240.mp4')) {
            unlink($path . '/video_320x240.mp4');
        }
    }
}
