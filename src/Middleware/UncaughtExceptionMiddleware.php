<?php declare(strict_types=1);

namespace Ellipse\Http\Middleware;

use Throwable;

use Interop\Http\Factory\ResponseFactoryInterface;

use Ellipse\Exceptions\ExceptionHandlerMiddleware;
use Ellipse\Http\Handlers\ExceptionRequestHandlerFactory;

class UncaughtExceptionMiddleware extends ExceptionHandlerMiddleware
{
    /**
     * Set up an exception handler middleware catching all errors with an
     * exception request handler factory using the given response factory and
     * debug mode.
     *
     * @param \Interop\Http\Factory\ResponseFactoryInterface    $factory
     * @param bool                                              $debug
     */
    public function __construct(ResponseFactoryInterface $factory, bool $debug)
    {
        parent::__construct(Throwable::class, new ExceptionRequestHandlerFactory($factory, $debug));
    }
}
