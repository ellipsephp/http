<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Negotiation\Negotiator;

class RequestBasedRequestHandler implements RequestHandlerInterface
{
    /**
     * The mediatype negotiator.
     *
     * @var \Negotiation\Negotiator
     */
    private $negotiator;

    /**
     * The associative array of mediatype to handler.
     *
     * @var array
     */
    private $handlers;

    /**
     * Set up a request based request handler with the given content type
     * negotiator and associative array of mediatype to handler.
     *
     * @param \Negotiation\Negotiator   $negotiator
     * @param array                     $handlers
     */
    public function __construct(Negotiator $negotiator, array $handlers)
    {
        $this->negotiator = $negotiator;
        $this->handlers = $handlers;
    }

    /**
     * When a handler is associated to application/json mediatype is present and
     * the request is an ajax request, proxy the associated request handler.
     * Otherwise proxy the preferred one based on the request accept header.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $json = $this->handlers['application/json'] ?? false;

        if ($json && $this->isAjax($request)) {

            return $json->handle($request);

        }

        return $this->preferred($request)->handle($request);
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
     * Return the preferred request handler. Fallback to the first one when no
     * request handler matches the request accept header.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Server\RequestHandlerInterface
     */
    private function preferred(ServerRequestInterface $request): RequestHandlerInterface
    {
        $accept = $request->getHeaderLine('Accept', '*/*');
        $priorities = array_keys($this->handlers);

        $best = $this->negotiator->getBest($accept, $priorities);

        $mediatype = $best ? $best->getValue() : current($priorities);

        return $this->handlers[$mediatype];
    }
}
