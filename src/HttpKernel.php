<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Dispatcher;
use Ellipse\Http\Middleware\HttpExceptionMiddleware;

class HttpKernel Extends AbstractHttpKernel
{
    public function __construct(RequestHandlerInterface $handler, bool $debug)
    {
        parent::__construct(new Dispatcher($handler, [
            new HttpExceptionMiddleware
        ]), $debug);
    }
}
