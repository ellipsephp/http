<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Ellipse\Http\Handlers\ExceptionRequestHandlerFactory;

class DefaultShutdownHandler extends ShutdownHandler
{
    /**
     * Set up a shutdown handler using a default exception request handler
     * factory with the given request and response prototype.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @param \Psr\Http\Message\ResponseInterface       $prototype
     * @param bool                                      $debug
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $prototype, bool $debug)
    {
        parent::__construct($request, new ExceptionRequestHandlerFactory($prototype, $debug));
    }
}
