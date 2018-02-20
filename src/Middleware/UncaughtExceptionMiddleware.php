<?php declare(strict_types=1);

namespace Ellipse\Http\Middleware;

use Throwable;

use Psr\Http\Message\ResponseInterface;

use Ellipse\Exceptions\ExceptionHandlerMiddleware;
use Ellipse\Http\Handlers\ExceptionRequestHandlerFactory;

class UncaughtExceptionMiddleware extends ExceptionHandlerMiddleware
{
    /**
     * Set up an exception handler middleware catching all errors and producing
     * a response with a server error request handler.
     *
     * @param \Psr\Http\Message\ResponseInterface   $prototype
     * @param bool                                  $debug
     */
    public function __construct(ResponseInterface $prototype, bool $debug)
    {
        parent::__construct(Throwable::class, new ExceptionRequestHandlerFactory($prototype, $debug));
    }
}
