<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SimpleJsonExceptionRequestHandler implements RequestHandlerInterface
{
    /**
     * The response prototype.
     *
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $prototype;

    /**
     * Set up a simple json exception request handler with the given response
     * prototype.
     *
     * @param \Psr\Http\Message\ResponseInterface $prototype
     */
    public function __construct(ResponseInterface $prototype)
    {
        $this->prototype = $prototype;
    }

    /**
     * Return a simple json response for the exception.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $contents = json_encode(['message' => 'Server error']);

        $this->prototype->getBody()->write($contents);

        return $this->prototype
            ->withStatus(500)
            ->withHeader('Content-type', 'application/json');
    }
}
