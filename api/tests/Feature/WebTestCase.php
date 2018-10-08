<?php

declare(strict_types=1);

namespace Api\Test\Feature;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

class WebTestCase extends TestCase
{
    protected function get(string $uri): ResponseInterface
    {
        return $this->method($uri, 'GET');
    }

    protected function method(string $uri, $method): ResponseInterface
    {
        return $this->request(
            (new ServerRequest())
                ->withUri(new Uri('http://test' . $uri))
                ->withMethod($method)
        );
    }

    protected function request(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->app()->process($request, new Response());
        $response->getBody()->rewind();
        return $response;
    }

    private function app(): App
    {
        $container = $this->container();
        $app = new App($container);
        (require 'config/routes.php')($app);
        return $app;
    }

    private function container(): ContainerInterface
    {
        return require 'config/container.php';
    }
}
