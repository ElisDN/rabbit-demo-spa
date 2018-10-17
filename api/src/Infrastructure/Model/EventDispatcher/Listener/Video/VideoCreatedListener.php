<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\EventDispatcher\Listener\Video;

use Api\Model\Video\Entity\Video\Event\VideoCreated;
use Kafka\Producer;

class VideoCreatedListener
{
    private $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
    }

    public function __invoke(VideoCreated $event)
    {
        $this->producer->send([
            [
                'topic' => 'notifications',
                'value' => json_encode([
                    'type' => 'notification',
                    'user_id' => $event->author->getId(),
                    'message' => 'Video created',
                ]),
                'key' => '',
            ],
        ]);
    }
}
