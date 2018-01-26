<?php declare(strict_types=1);

namespace Ellipse\Http\Middleware;

use Throwable;

use Ellipse\Exceptions\ExceptionHandlerMiddleware;
use Ellipse\Http\Exceptions\Response\RequestBasedResponseFactory;

class ServerErrorMiddleware extends ExceptionHandlerMiddleware
{
    /**
     * Set up an exception handler middleware catching all errors and producing
     * a response with the given response factory.
     *
     * @param \Ellipse\Http\Exceptions\Response\RequestBasedResponseFactory $factory
     */
    public function __construct(RequestBasedResponseFactory $factory)
    {
        parent::__construct(Throwable::class, [$factory, 'response']);
    }
}
