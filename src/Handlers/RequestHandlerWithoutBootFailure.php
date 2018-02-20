<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Dispatcher\RequestHandlerWithMiddlewareStack;
use Ellipse\Http\Middleware\HttpExceptionMiddleware;

class RequestHandlerWithoutBootFailure extends RequestHandlerWithMiddlewareStack
{
    /**
     * Set up a request handler without boot failure with the given request
     * handler wrapped inside a http exception middleware.
     *
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     */
    public function __construct(RequestHandlerInterface $handler)
    {
        parent::__construct($handler, [new HttpExceptionMiddleware]);
    }
}
