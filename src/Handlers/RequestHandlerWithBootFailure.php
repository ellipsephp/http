<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Exceptions\BootException;

class RequestHandlerWithBootFailure implements RequestHandlerInterface
{
    /**
     * The exception thrown during booting.
     *
     * @var \Throwable
     */
    private $e;

    /**
     * Set up a boot exception request handler with the given exception.
     *
     * @param \Throwable $e
     */
    public function __construct(Throwable $e)
    {
        $this->e = $e;
    }

    /**
     * Throws the exception wrapped inside a boot exception.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Ellipse\Http\Exceptions\BootException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        throw new BootException($this->e);
    }
}
