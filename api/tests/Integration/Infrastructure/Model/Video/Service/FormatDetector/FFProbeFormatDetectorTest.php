<?php

declare(strict_types=1);

namespace Api\Test\Integration\Infrastructure\Model\Video\Service\FormatDetector;

use Api\Infrastructure\Model\Video\Service\Processor\FormatDetector\FFProbeFormatDetector;
use PHPUnit\Framework\TestCase;

class FFProbeFormatDetectorTest extends TestCase
{
    /**
     * @var FFProbeFormatDetector
     */
    private $detector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detector = new FFProbeFormatDetector(\dirname(__DIR__) . '/data');
    }

    public function testMp4(): void
    {
        $video = $this->detector->detect('video.mp4');

        self::assertEquals('video.mp4', $video->getPath());
        self::assertEquals('mp4', $video->getFormat()->getName());
        self::assertEquals(560, $video->getSize()->getWidth());
        self::assertEquals(320, $video->getSize()->getHeight());
    }

    public function test3gp(): void
    {
        $video = $this->detector->detect('video.3gp');

        self::assertEquals('video.3gp', $video->getPath());
        self::assertEquals('3gp', $video->getFormat()->getName());
        self::assertEquals(352, $video->getSize()->getWidth());
        self::assertEquals(288, $video->getSize()->getHeight());
    }
}
