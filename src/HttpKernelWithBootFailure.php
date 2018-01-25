<?php declare(strict_types=1);

namespace Ellipse\Http;

use Throwable;

use Ellipse\Http\Handlers\BootExceptionRequestHandler;

class HttpKernelWithBootFailure Extends AbstractHttpKernel
{
    public function __construct(Throwable $e, bool $debug)
    {
        parent::__construct(new BootExceptionRequestHandler($e), $debug);
    }
}
