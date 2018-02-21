<?php declare(strict_types=1);

namespace Ellipse\Http;

use Throwable;

use Interop\Http\Factory\ResponseFactoryInterface;

use Ellipse\Http\Handlers\RequestHandlerWithBootFailure;

class HttpKernelWithBootFailure extends HttpKernel
{
    /**
     * Set up a http kernel with a request handler with boot failure.
     *
     * @param \Throwable                                        $e
     * @param \Interop\Http\Factory\ResponseFactoryInterface    $factory
     * @param bool                                              $debug
     */
    public function __construct(Throwable $e, ResponseFactoryInterface $factory, bool $debug)
    {
        parent::__construct(new RequestHandlerWithBootFailure($e), $factory, $debug);
    }
}
