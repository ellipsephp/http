<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Dispatcher\RequestHandlerWithMiddlewareStack;
use Ellipse\Http\Middleware\UncaughtExceptionMiddleware;

class HttpKernel extends RequestHandlerWithMiddlewareStack
{
    /**
     * Set up a http kernel with the given request handler wrapped inside a
     * server error middleware.
     *
     * @param \Psr\Http\Server\RequestHandlerInterface  $handler
     * @param \Psr\Http\Message\ResponseInterface       $prototype
     * @param bool                                      $debug
     */
    public function __construct(RequestHandlerInterface $handler, ResponseInterface $prototype, bool $debug)
    {
        parent::__construct($handler, [new UncaughtExceptionMiddleware($prototype, $debug)]);
    }
}
