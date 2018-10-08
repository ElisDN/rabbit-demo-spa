<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

(new Dotenv())->load('.env');