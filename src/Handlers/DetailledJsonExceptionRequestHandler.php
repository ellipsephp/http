<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Interop\Http\Factory\ResponseFactoryInterface;

use Ellipse\Http\Exceptions\Inspector;

class DetailledJsonExceptionRequestHandler implements RequestHandlerInterface
{
    /**
     * The caught exception.
     *
     * @var \Throwable
     */
    private $e;

    /**
     * The response factory.
     *
     * @var \Interop\Http\Factory\ResponseFactoryInterface
     */
    private $factory;

    /**
     * Set up a detailled json exception request handler with the given
     * exception and response factory.
     *
     * @param \Throwable                                        $e
     * @param \Interop\Http\Factory\ResponseFactoryInterface    $factory
     */
    public function __construct(Throwable $e, ResponseFactoryInterface $factory)
    {
        $this->e = $e;
        $this->factory = $factory;
    }

    /**
     * Return a detailled json response for the exception.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $details = new Inspector($this->e);

        $contents = json_encode([
            'type' => get_class($details->inner()),
            'message' => $details->inner()->getMessage(),
            'context' => [
                'type' => get_class($details->current()),
                'message' => $details->current()->getMessage(),
            ],
            'trace' => $details->inner()->getTrace(),
        ]);

        $response = $this->factory
            ->createResponse(500)
            ->withHeader('Content-type', 'application/json');

        $response->getBody()->write($contents);

        return $response;
    }
}
