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
