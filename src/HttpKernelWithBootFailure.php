<?php declare(strict_types=1);

namespace Ellipse\Http;

use Throwable;

use Psr\Http\Message\ResponseInterface;

use Ellipse\Http\Handlers\RequestHandlerWithBootFailure;

class HttpKernelWithBootFailure extends HttpKernel
{
    /**
     * Set up a http kernel with a request handler with boot failure.
     *
     * @param \Throwable                            $e
     * @param \Psr\Http\Message\ResponseInterface   $prototype
     * @param bool                                  $debug
     */
    public function __construct(Throwable $e, ResponseInterface $prototype, bool $debug)
    {
        parent::__construct(new RequestHandlerWithBootFailure($e), $prototype, $debug);
    }
}
