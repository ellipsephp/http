<?php declare(strict_types=1);

namespace Ellipse\Http\Error;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Ellipse\Http\Handlers\ExceptionRequestHandlerFactory;

class DefaultErrorHandler extends ErrorHandler
{
    /**
     * Set up an exception handler using the given request and a default
     * exception request handler factory, using the given response prototype and
     * debug mode.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @param \Psr\Http\Message\ResponseInterface       $prototype
     * @param bool                                      $debug
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $prototype, bool $debug)
    {
        parent::__construct($request, new ExceptionRequestHandlerFactory($prototype, $debug));
    }
}
