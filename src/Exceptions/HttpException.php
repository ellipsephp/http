<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions;

use Throwable;
use RuntimeException;

class HttpException extends RuntimeException implements HttpExceptionInterface
{
    public function __construct(Throwable $previous)
    {
        parent::__construct("Uncaught exception while processing the http request", 0, $previous);
    }
}
