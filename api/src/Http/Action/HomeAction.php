<?php

declare(strict_types=1);

namespace Api\Http\Action;

use Slim\Http\Request;
use Slim\Http\Response;

class HomeAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        return $response->withJson([
            'name' => 'App API',
            'version' => '1.0',
        ]);
    }
}
