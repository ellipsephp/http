<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Dispatcher;
use Ellipse\Http\Middleware\HttpExceptionMiddleware;

class HttpKernelWithoutBootFailure extends HttpKernel
{
    /**
     * Set up a http kernel with the given request handler wrapped inside a http
     * exception middleware.
     *
     * @param \Psr\Http\Server\RequestHandlerInterface  $handler
     * @param bool                                      $debug
     */
    public function __construct(RequestHandlerInterface $handler, bool $debug)
    {
        parent::__construct(new Dispatcher($handler, [new HttpExceptionMiddleware]), $debug);
    }
}
