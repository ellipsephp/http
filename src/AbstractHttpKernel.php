<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Dispatcher;
use Ellipse\Http\Middleware\ServerErrorMiddleware;
use Ellipse\Http\Exceptions\Response\DefaultRequestBasedResponseFactory;

abstract class AbstractHttpKernel extends Dispatcher
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
        parent::__construct($handler, [
            new ServerErrorMiddleware(
                new DefaultRequestBasedResponseFactory($debug)
            ),
        ]);
    }
}
