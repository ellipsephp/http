<?php declare(strict_types=1);

namespace Ellipse\Http\Middleware;

use Throwable;

use Ellipse\Http\Exceptions\Response\RequestBasedResponseFactory;

class ServerErrorMiddleware extends ExceptionHandlerMiddleware
{
    public function __construct(RequestBasedResponseFactory $factory)
    {
        parent::__construct(Throwable::class, [$factory, 'response']);
    }
}
