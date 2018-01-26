<?php declare(strict_types=1);

namespace Ellipse\Http;

use Throwable;

use Ellipse\Http\Handlers\BootExceptionRequestHandler;

class HttpKernelWithBootFailure Extends AbstractHttpKernel
{
    /**
     * Set up a http kernel with a boot exception request handler wrapped around
     * the given exception.
     *
     * @param \Throwable    $handler
     * @param bool          $debug
     */
    public function __construct(Throwable $e, bool $debug)
    {
        parent::__construct(new BootExceptionRequestHandler($e), $debug);
    }
}
