<?php

declare(strict_types=1);

use Api\Http\Action;
use Slim\App;

return function (App $app) {

    $app->get('/', Action\HomeAction::class . ':handle');

};