<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Handlers\RequestHandlerWithoutBootFailure;

class HttpKernelWithoutBootFailure extends HttpKernel
{
    /**
     * Set up a http kernel with the given request handler wrapped inside a http
     * exception middleware.
     *
     * @param \Psr\Http\Server\RequestHandlerInterface  $handler
     * @param \Psr\Http\Message\ResponseInterface       $prototype
     * @param bool                                      $debug
     */
    public function __construct(RequestHandlerInterface $handler, ResponseInterface $prototype, bool $debug)
    {
        parent::__construct(new RequestHandlerWithoutBootFailure($handler), $prototype, $debug);
    }
}
