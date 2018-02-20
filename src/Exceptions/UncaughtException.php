<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions;

use Throwable;
use Exception;

class UncaughtException extends Exception implements HttpExceptionInterface
{
    public function __construct(Throwable $previous)
    {
        parent::__construct("Uncaught exception", 0, $previous);
    }
}
