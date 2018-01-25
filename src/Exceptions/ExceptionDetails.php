<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions;

use Throwable;

class ExceptionDetails
{
    private $e;

    public function __construct(Throwable $e)
    {
        $this->e = $e;
    }

    public function current(): Throwable
    {
        return $this->e;
    }

    public function inner(): Throwable
    {
        return current($this->linearized());
    }

    public function linearized(): array
    {
        return $this->linearize($this->e);
    }

    private function linearize(Throwable $e): array
    {
        $previous = $e->getPrevious();

        if (is_null($previous)) return [$e];

        return array_merge($this->linearize($previous), [$e]);
    }
}
