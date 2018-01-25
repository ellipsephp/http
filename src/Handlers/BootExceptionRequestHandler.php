<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Exceptions\BootException;

class BootExceptionRequestHandler implements RequestHandlerInterface
{
    private $e;

    public function __construct(Throwable $e)
    {
        $this->e = $e;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        throw new BootException($this->e);
    }
}
