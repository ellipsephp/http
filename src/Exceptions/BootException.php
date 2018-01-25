<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions;

use Throwable;
use Exception;

class BootException extends Exception
{
    public function __construct(Throwable $previous)
    {
        parent::__construct("Uncaught exception while booting the http kernel", 0, $previous);
    }
}
