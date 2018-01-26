<?php declare(strict_types=1);

namespace Ellipse\Http\Middleware;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Exceptions\HttpException;

class HttpExceptionMiddleware implements MiddlewareInterface
{
    /**
     * Proxy the given handler and wrap any exception in a http exception.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @param \Psr\Http\Server\RequestHandlerInterface  $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Ellipse\Http\Exceptions\HttpException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {

            return $handler->handle($request);

        }

        catch (Throwable $e) {

            throw new HttpException($e);

        }
    }
}
