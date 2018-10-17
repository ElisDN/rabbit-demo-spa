<?php

declare(strict_types=1);

namespace Api\Console\Command\Amqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeCommand extends Command
{
    private $connection;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('amqp:demo:consume');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Consume messages</comment>');

        $exchange = 'notifications';
        $queue = 'messages';

        $connection = $this->connection;

        $channel = $connection->channel();

        $channel->queue_declare($queue, false, false, false, false);
        $channel->exchange_declare($exchange, 'fanout', false, false, false);
        $channel->queue_bind($queue, $exchange);

        $consumerTag = 'consumer_' . getmypid();
        $channel->basic_consume($queue, $consumerTag, false, false, false, false, function ($message) use ($output)
        {
            $output->writeln(print_r(json_decode($message->body, true), true));

            /** @var AMQPChannel $channel */
            $channel = $message->delivery_info['channel'];
            $channel->basic_ack($message->delivery_info['delivery_tag']);
        });

        register_shutdown_function(function (AMQPChannel $channel, AMQPStreamConnection $connection) {
            $channel->close();
            $connection->close();
        }, $channel, $connection);

        while (\count($channel->callbacks)) {
            $channel->wait();
        }

        $output->writeln('<info>Done!</info>');
    }
}
