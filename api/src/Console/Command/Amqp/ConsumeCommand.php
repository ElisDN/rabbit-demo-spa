<?php

declare(strict_types=1);

namespace Api\Console\Command\Amqp;

use Api\Infrastructure\Amqp\AMQPHelper;
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

        $connection = $this->connection;

        $channel = $connection->channel();

        AMQPHelper::initNotifications($channel);
        AMQPHelper::registerShutdown($connection, $channel);

        $consumerTag = 'consumer_' . getmypid();
        $channel->basic_consume(AMQPHelper::QUEUE_NOTIFICATIONS, $consumerTag, false, false, false, false, function ($message) use ($output)
        {
            $output->writeln(print_r(json_decode($message->body, true), true));

            /** @var AMQPChannel $channel */
            $channel = $message->delivery_info['channel'];
            $channel->basic_ack($message->delivery_info['delivery_tag']);
        });

        while (\count($channel->callbacks)) {
            $channel->wait();
        }

        $output->writeln('<info>Done!</info>');
    }
}
