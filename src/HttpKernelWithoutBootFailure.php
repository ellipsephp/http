<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Http\Server\RequestHandlerInterface;

use Interop\Http\Factory\ResponseFactoryInterface;

use Ellipse\Http\Handlers\RequestHandlerWithoutBootFailure;

class HttpKernelWithoutBootFailure extends HttpKernel
{
    /**
     * Set up a http kernel with a request handler without boot failure.
     *
     * @param \Psr\Http\Server\RequestHandlerInterface          $handler
     * @param \Interop\Http\Factory\ResponseFactoryInterface    $factory
     * @param bool                                              $debug
     */
    public function __construct(RequestHandlerInterface $handler, ResponseFactoryInterface $factory, bool $debug)
    {
        parent::__construct(new RequestHandlerWithoutBootFailure($handler), $factory, $debug);
    }
}
