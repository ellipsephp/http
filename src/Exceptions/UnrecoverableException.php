<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions;

use Throwable;
use Exception;

class UnrecoverableException extends Exception
{
    public function __construct(Throwable $previous)
    {
        parent::__construct("An unrecoverable error occurred", 0, $previous);
    }
}
