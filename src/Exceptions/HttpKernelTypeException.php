<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions;

use Exception;

use Psr\Http\Server\RequestHandlerInterface;

class HttpKernelTypeException extends Exception
{
    public function __construct($value)
    {
        $template = "Trying to use a value of type %s as http kernel - object implementing %s expected";

        $type = is_object($value) ? get_class($value) : gettype($value);

        $msg = sprintf($template, $type, RequestHandlerInterface::class);

        parent::__construct($msg);
    }
}
