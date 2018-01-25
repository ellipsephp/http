<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Negotiation\Negotiator;

class RequestBasedResponseFactory
{
    private $negotiator;
    private $factories;

    public function __construct(array $factories)
    {
        $this->negotiator = new Negotiator;
        $this->factories = $factories;
    }

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
