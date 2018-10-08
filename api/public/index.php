<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

if (file_exists('.env')) {
    (new Dotenv())->load('.env');
}

(function () {
    $container = require 'config/container.php';
    $app = new \Slim\App($container);
    (require 'config/routes.php')($app, $container);
    $app->run();
})();