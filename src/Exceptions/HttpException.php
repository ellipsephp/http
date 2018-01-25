<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions;

use Throwable;
use Exception;

class HttpException extends Exception
{
    public function __construct(Throwable $previous)
    {
        parent::__construct("Uncaught exception while processing the http request", 0, $previous);
    }
}
