<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Interop\Http\Factory\ResponseFactoryInterface;

use League\Plates\Engine;

use Ellipse\Http\Exceptions\Inspector;

class DetailledHtmlExceptionRequestHandler implements RequestHandlerInterface
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
     * Set up a detailled html exception request handler with the given
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
     * Return a detailled html response for the exception.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $path = realpath(__DIR__ . '/../../templates');

        $engine = new Engine($path);

        $contents = $engine->render('detailled', [
            'details' => new Inspector($this->e),
        ]);

        $response = $this->factory
            ->createResponse(500)
            ->withHeader('Content-type', 'text/html');

        $response->getBody()->write($contents);

        return $response;
    }
}
