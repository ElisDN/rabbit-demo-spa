<?php

declare(strict_types=1);

namespace Api\Infrastructure\Framework\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Handlers\PhpError;

class LogPhpHandler extends PhpError
{
    protected $logger;

    public function __construct(LoggerInterface $logger, bool $displayErrorDetails = false)
    {
        $this->logger = $logger;
        parent::__construct($displayErrorDetails);
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, \Throwable $error)
    {
        $this->logger->error($error->getMessage(), ['exception' => $error]);

        return parent::__invoke($request, $response, $error);
    }
}