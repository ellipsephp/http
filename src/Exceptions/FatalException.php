<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions;

use Throwable;
use RuntimeException;

class FatalException extends RuntimeException implements HttpExceptionInterface
{
    public function __construct(Throwable $previous)
    {
        parent::__construct("A fatal error occurred", 0, $previous);
    }
}
