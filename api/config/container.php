<?php

declare(strict_types=1);

$config = require __DIR__ . '/config.php';

return new \Slim\Container($config);