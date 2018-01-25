<?php declare(strict_types=1);

namespace Ellipse\Http\Middleware;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Exceptions\Response\RequestBasedResponseFactory;

class ExceptionHandlerMiddleware implements MiddlewareInterface
{
    public $class;
    public $callable;

    public function __construct(string $class, callable $callable)
    {
        $this->class = $class;
        $this->callable = $callable;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {

            return $handler->handle($request);

        }

        catch (Throwable $e) {

            if ($e instanceof $this->class) {

                return ($this->callable)($request, $e);

            }

        }
    }
}
