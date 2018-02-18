<?php declare(strict_types=1);

namespace Ellipse\Http\Middleware;

use Negotiation\Negotiator;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JsonHandlerMiddleware implements MiddlewareInterface
{
    /**
     * The media type negotiator.
     *
     * @var \Negotiation\Negotiator
     */
    private $negotiator;

    /**
     * The media type priorities.
     *
     * @var array
     */
    private $priorities;

    /**
     * The json request handler.
     *
     * @var \Psr\Http\Server\RequestHandlerInterface
     */
    private $handler;

    /**
     * Set up a json handler middleware with the given negotiator, priorities
     * and json request handler.
     *
     * @param \Negotiation\Nagotiator                   $negotiator
     * @param array                                     $priorities
     * @param \Psr\Http\Server\RequestHandlerInterface  $handler
     */
    public function __construct(Negotiator $negotiator, array $priorities, RequestHandlerInterface $handler)
    {
        $this->negotiator = $negotiator;
        $this->priorities = $priorities;
        $this->handler = $handler;
    }

    /**
     * Use the json request handler to produce a response when the request
     * accepts json. Otherwise use the given request handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @param \Psr\Http\Server\RequestHandlerInterface  $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->isAjax($request) || $this->prefersJson($request)) {

            return $this->handler->handle($request);

        }

        return $handler->handle($request);
    }

    /**
     * Return whether the request is an ajax request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return bool
     */
    private function isAjax(ServerRequestInterface $request): bool
    {
        $params = $request->getServerParams();

        if (! array_key_exists('HTTP_X_REQUESTED_WITH', $params)) {

            return false;

        }

        return strtolower($params['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * Return whether the request prefers json content.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return bool
     */
    private function prefersJson(ServerRequestInterface $request): bool
    {
        $accept = $request->getHeaderLine('Accept', '*/*');

        $mediatype = $this->negotiator->getBest($accept, $this->priorities);

        if (is_null($mediatype)) {

            return false;

        }

        return $mediatype->getValue() == 'application/json';
    }
}
