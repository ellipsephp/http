<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions;

use Throwable;

class Inspector
{
    /**
     * The exception to inspect.
     *
     * @var \Throwable
     */
    private $e;

    /**
     * Set up an exception details with the given exception.
     *
     * @param \Throwable $e
     */
    public function __construct(Throwable $e)
    {
        $this->e = $e;
    }

    /**
     * Return the exception.
     *
     * @return \Throwable
     */
    public function current(): Throwable
    {
        return $this->e;
    }

    /**
     * Return the inner exception.
     *
     * @return \Throwable
     */
    public function inner(): Throwable
    {
        return current($this->linearized());
    }

    /**
     * Return an array containing the exception and all its previous exceptions.
     *
     * @return array
     */
    public function linearized(): array
    {
        return $this->linearize($this->e);
    }

    /**
     * Return an array containing the given exception and all its previous
     * exceptions.
     *
     * @param \Throwable $e
     * @return array
     */
    private function linearize(Throwable $e): array
    {
        $previous = $e->getPrevious();

        if (is_null($previous)) return [$e];

        return array_merge($this->linearize($previous), [$e]);
    }
}
