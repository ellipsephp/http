<?php declare(strict_types=1);

namespace Ellipse\Http\Error;

use Psr\Http\Message\ServerRequestInterface;

use Interop\Http\Factory\ResponseFactoryInterface;

use Ellipse\Http\Handlers\ExceptionRequestHandlerFactory;

class DefaultErrorHandler extends ErrorHandler
{
    /**
     * Set up an exception handler using the given request and a default
     * exception request handler factory, using the given response factory and
     * debug mode.
     *
     * @param \Psr\Http\Message\ServerRequestInterface          $request
     * @param \Interop\Http\Factory\ResponseFactoryInterface    $factory
     * @param bool                                              $debug
     */
    public function __construct(ServerRequestInterface $request, ResponseFactoryInterface $factory, bool $debug)
    {
        parent::__construct($request, new ExceptionRequestHandlerFactory($factory, $debug));
    }
}
