<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

if (file_exists('.env')) {
    (new Dotenv())->load('.env');
}

$config = require 'config/config.php';
$container = new \Slim\Container($config);
$app = new \Slim\App($container);

(require 'config/routes.php')($app);

$app->run();