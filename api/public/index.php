<?php

declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

header('Content-Type: application/json');

echo json_encode([
    'name' => 'App API',
    'version' => '1.0',
]);