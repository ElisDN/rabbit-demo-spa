<?php

declare(strict_types=1);

use Api\Http\VideoUrl;
use Api\Infrastructure\Model\Video\Service\Processor\Converter\FFMpegMp4Converter;
use Api\Infrastructure\Model\Video\Service\Processor\Converter\FFMpegWebmConverter;
use Api\Infrastructure\Model\Video\Service\Processor\FormatDetector\FFProbeFormatDetector;
use Api\Infrastructure\Model\Video\Service\Processor\Thumbnailer\FFMpegThumbnailer;
use Api\Infrastructure\Model\Video\Service\Uploader\LocalUploader;
use Api\Model\Video\Service\Processor\Converter\Converter;
use Api\Model\Video\Service\Processor\Format;
use Api\Model\Video\Service\Processor\FormatDetector;
use Api\Model\Video\Service\Processor\Size;
use Api\Model\Video\Service\Processor\Thumbnailer\Thumbnailer;
use Api\Model\Video\Service\Uploader;
use Api\Model\Video\UseCase\Video\Create\Preferences;
use Psr\Container\ContainerInterface;

return [
    Uploader::class => function (ContainerInterface $container) {
        return new LocalUploader($container->get('config')['video']['upload_path']);
    },

    Converter::class => function (ContainerInterface $container) {
        return new Converter([
            new FFMpegWebmConverter($container->get('config')['video']['upload_path']),
            new FFMpegMp4Converter($container->get('config')['video']['upload_path'])
        ]);
    },

    Thumbnailer::class => function (ContainerInterface $container) {
        return new Thumbnailer([
            new FFMpegThumbnailer($container->get('config')['video']['upload_path']),
        ]);
    },

    FormatDetector::class => function (ContainerInterface $container) {
        return new FFProbeFormatDetector(
            $container->get('config')['video']['upload_path']
        );
    },

    Preferences::class => function (ContainerInterface $container) {
        $preferences = new Preferences();

        $preferences->thumbnailSize = new Size(854, 480);

        $preferences->videoSizes = array_map(function (array $size) {
            return new Size($size[0], $size[1]);
        }, $container->get('config')['video']['sizes']);

        $preferences->videoFormats = [
            new Format('webm'),
            new Format('mp4'),
        ];

        return $preferences;
    },

    VideoUrl::class => function (ContainerInterface $container) {
        return new VideoUrl(
            $container->get('config')['video']['base_url']
        );
    },

    'config' => [
        'video' => [
            'upload_path' => dirname(__DIR__, 3) . '/storage/public/video',
            'base_url' => getenv('API_STORAGE_URL'),
            'sizes' => [
                [640, 360],
                [854, 480],
                [1280, 720],
                [1920, 1080],
            ],
        ],
    ],
];
