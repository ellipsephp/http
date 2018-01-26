<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions;

use Throwable;
use Exception;

class FatalException extends Exception
{
    public function __construct(Throwable $previous)
    {
        parent::__construct("A fatal error occurred", 0, $previous);
    }
}
