<?php

declare(strict_types=1);

use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

$aggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/common/*.php'),
    new PhpFileProvider(__DIR__ . '/' . (getenv('API_ENV') ?: 'prod') . '/*.php'),
]);

$config = $aggregator->getMergedConfig();

return new \Slim\Container($config);