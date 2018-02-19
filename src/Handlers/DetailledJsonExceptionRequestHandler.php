<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Zend\Diactoros\Response\JsonResponse;

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
     * Set up a detailled json exception request handler with the given
     * exception.
     *
     * @param \Throwable $e
     */
    public function __construct(Throwable $e)
    {
        $this->e = $e;
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

        return new JsonResponse([
            'type' => get_class($details->inner()),
            'message' => $details->inner()->getMessage(),
            'context' => [
                'type' => get_class($details->current()),
                'message' => $details->current()->getMessage(),
            ],
            'trace' => $details->inner()->getTrace(),
        ], 500);
    }
}
