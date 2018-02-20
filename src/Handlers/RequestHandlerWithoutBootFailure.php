<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Dispatcher\RequestHandlerWithMiddlewareStack;
use Ellipse\Http\Middleware\HttpExceptionMiddleware;

class RequestHandlerWithoutBootFailure extends RequestHandlerWithMiddlewareStack
{
    public function __construct(RequestHandlerInterface $handler)
    {
        parent::__construct($handler, [new HttpExceptionMiddleware]);
    }
}
