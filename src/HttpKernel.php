<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Dispatcher\RequestHandlerWithMiddlewareStack;
use Ellipse\Http\Middleware\ServerErrorMiddleware;

class HttpKernel extends RequestHandlerWithMiddlewareStack
{
    /**
     * Set up a http kernel with the given request handler wrapped inside a
     * server error middleware.
     *
     * @param \Psr\Http\Server\RequestHandlerInterface  $handler
     * @param bool                                      $debug
     */
    public function __construct(RequestHandlerInterface $handler, bool $debug)
    {
        $path = realpath(__DIR__ . '/../templates');

        parent::__construct($handler, [new ServerErrorMiddleware($path, $debug)]);
    }
}
