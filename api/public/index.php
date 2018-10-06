<?php

declare(strict_types=1);

use Api\Http\Action;
use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

if (file_exists('.env')) {
    (new Dotenv())->load('.env');
}

$config = require 'config/config.php';
$container = new \Slim\Container($config);
$app = new \Slim\App($container);

$app->get('/', Action\HomeAction::class . ':handle');

$app->run();