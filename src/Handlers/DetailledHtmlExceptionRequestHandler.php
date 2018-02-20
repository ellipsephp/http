<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
     * The response prototype.
     *
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $prototype;

    /**
     * Set up a detailled html exception request handler with the given
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

        $this->prototype->getBody()->write($contents);

        return $this->prototype
            ->withStatus(500)
            ->withHeader('Content-type', 'text/html');
    }
}
