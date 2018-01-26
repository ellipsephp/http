<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Psr\Http\Server\RequestHandlerInterface;

use Zend\Diactoros\Response\TextResponse;

class DefaultHttpRequestHandler implements RequestHandlerInterface
{
    /**
     * Return a default response when nothing returned a response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new TextResponse("This is the default request handler. Nothing returned a response before hitting it.");
    }
}
