<?php

declare(strict_types=1);

namespace Api\Infrastructure\Amqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AMQPHelper
{
    public const EXCHANGE_NOTIFICATIONS = 'notifications';
    public const QUEUE_NOTIFICATIONS = 'notifications';

    public static function initNotifications(AMQPChannel $channel): void
    {
        $channel->queue_declare(self::QUEUE_NOTIFICATIONS, false, false, false, false);
        $channel->exchange_declare(self::EXCHANGE_NOTIFICATIONS, 'fanout', false, false, false);
        $channel->queue_bind(self::QUEUE_NOTIFICATIONS, self::EXCHANGE_NOTIFICATIONS);
    }

    public static function registerShutdown(AMQPStreamConnection $connection, AMQPChannel $channel): void
    {
        register_shutdown_function(function (AMQPChannel $channel, AMQPStreamConnection $connection) {
            $channel->close();
            $connection->close();
        }, $channel, $connection);
    }
}
