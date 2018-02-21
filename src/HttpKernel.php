<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Http\Server\RequestHandlerInterface;

use Interop\Http\Factory\ResponseFactoryInterface;

use Ellipse\Dispatcher\RequestHandlerWithMiddlewareStack;
use Ellipse\Http\Middleware\UncaughtExceptionMiddleware;

class HttpKernel extends RequestHandlerWithMiddlewareStack
{
    /**
     * Set up a http kernel with the given request handler wrapped inside an
     * uncaught exception middleware, using the given response factory and debug
     * mode.
     *
     * @param \Psr\Http\Server\RequestHandlerInterface          $handler
     * @param \Interop\Http\Factory\ResponseFactoryInterface    $factory
     * @param bool                                              $debug
     */
    public function __construct(RequestHandlerInterface $handler, ResponseFactoryInterface $factory, bool $debug)
    {
        parent::__construct($handler, [new UncaughtExceptionMiddleware($factory, $debug)]);
    }
}
