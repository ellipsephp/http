<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
     * The response prototype.
     *
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $prototype;

    /**
     * Set up a detailled json exception request handler with the given
     * exception and response prototype.
     *
     * @param \Throwable                            $e
     * @param \Psr\Http\Message\ResponseInterface   $prototype
     */
    public function __construct(Throwable $e, ResponseInterface $prototype)
    {
        $this->e = $e;
        $this->prototype = $prototype;
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

        $this->prototype->getBody()->write($contents);

        return $this->prototype
            ->withStatus(500)
            ->withHeader('Content-type', 'application/json');
    }
}
