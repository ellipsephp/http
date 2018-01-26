<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Negotiation\Negotiator;

class RequestBasedResponseFactory
{
    /**
     * The negotiator used to choose a response factory according to the request
     * accept header.
     *
     * @var \Negotiation\Negotiator
     */
    private $negotiator;

    /**
     * The factories to use in order to produce a response.
     *
     * @var array
     */
    private $factories;

    /**
     * Set up a request based response factory with the given factories.
     *
     * @param array $factories
     */
    public function __construct(array $factories)
    {
        $this->negotiator = new Negotiator;
        $this->factories = $factories;
    }

    /**
     * Use the negotiator to choose a factory based on the request accept
     * header. Then use the factory to produce a response from the exception.
     *
     * When no factory match the accept header, use the first one as default.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @param \Throwable                                $e
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response(ServerRequestInterface $request, Throwable $e): ResponseInterface
    {
        $accept = $request->getHeaderLine('Accept', '*/*');
        $priorities = array_keys($this->factories);

        $mediatype = $this->negotiator->getBest($accept, $priorities);

        $factory = $mediatype
            ? $this->factories[$mediatype->getValue()]
            : current($this->factories);

        return $factory->response($e);
    }
}
