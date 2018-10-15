<?php

declare(strict_types=1);

namespace Api\Console\Command\Kafka;

use Kafka\Consumer;
use Kafka\ConsumerConfig;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeCommand extends Command
{
    private $logger;
    private $brokers;

    public function __construct(LoggerInterface $logger, string $brokers)
    {
        $this->logger = $logger;
        $this->brokers = $brokers;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('kafka:demo:consume');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Consume messages</comment>');

        $config = ConsumerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList($this->brokers);
        $config->setBrokerVersion('1.1.0');
        $config->setGroupId('demo');
        $config->setTopics(['notifications']);

        $consumer = new Consumer();
        $consumer->setLogger($this->logger);

        $consumer->start(function($topic, $part, $message) use ($output) {
            $output->writeln(print_r($message, true));
        });

        $output->writeln('<info>Done!</info>');
    }
}
