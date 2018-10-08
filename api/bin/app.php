#!/usr/bin/env php
<?php

declare(strict_types=1);

use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

if (file_exists('.env')) {
    (new Dotenv())->load('.env');
}

/**
 * @var \Psr\Container\ContainerInterface $container
 */
$container = require 'config/container.php';

$cli = new Application('Application console');

$commands = $container->get('config')['console']['commands'];
foreach ($commands as $command) {
    $cli->add($container->get($command));
}

$cli->run();
