<?php

declare(strict_types=1);

header('Content-Type: application/json');

echo json_encode([
    'name' => 'App API',
    'version' => '1.0',
]);