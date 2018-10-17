<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\EventDispatcher\Listener\Video;

use Api\Infrastructure\Amqp\AMQPHelper;
use Api\Model\Video\Entity\Video\Event\VideoCreated;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class VideoCreatedListener
{
    private $connection;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(VideoCreated $event)
    {
        $connection = $this->connection;

        $channel = $this->connection->channel();

        AMQPHelper::initNotifications($channel);
        AMQPHelper::registerShutdown($connection, $channel);

        $data = [
            'type' => 'notification',
            'user_id' => $event->author->getId(),
            'message' => 'Video created',
        ];

        $message = new AMQPMessage(
            json_encode($data),
            ['content_type' => 'text/plain']
        );

        $channel->basic_publish($message, AMQPHelper::EXCHANGE_NOTIFICATIONS);
    }
}
