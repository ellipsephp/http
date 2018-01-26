<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions;

use Throwable;
use RuntimeException;

class BootException extends RuntimeException implements HttpExceptionInterface
{
    public function __construct(Throwable $previous)
    {
        parent::__construct("Uncaught exception while booting the http kernel", 0, $previous);
    }
}
