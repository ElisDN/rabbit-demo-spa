<?php

declare(strict_types=1);

namespace Api\Console\Command\Kafka;

use Kafka\Producer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProduceCommand extends Command
{
    private $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('kafka:demo:produce')
            ->addArgument('user_id', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Produce message</comment>');

        $this->producer->send([
            [
                'topic' => 'notifications',
                'value' => json_encode([
                    'type' => 'notification',
                    'user_id' => $input->getArgument('user_id'),
                    'message' => 'Hello!',
                ]),
                'key' => '',
            ],
        ]);

        $output->writeln('<info>Done!</info>');
    }
}
