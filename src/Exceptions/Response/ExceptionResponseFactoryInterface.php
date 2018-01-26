<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

use Throwable;

use Psr\Http\Message\ResponseInterface;

interface ExceptionResponseFactoryInterface
{
    /**
     * Return a response for the given exception.
     *
     * @param \Throwable $e
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response(Throwable $e): ResponseInterface;
}
