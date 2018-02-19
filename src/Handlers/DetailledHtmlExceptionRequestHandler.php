<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use League\Plates\Engine;

use Zend\Diactoros\Response\HtmlResponse;

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
     * Set up a detailled html exception request handler with the given
     * exception.
     *
     * @param \Throwable $e
     */
    public function __construct(Throwable $e)
    {
        $this->e = $e;
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

        $html = $engine->render('detailled', [
            'details' => new Inspector($this->e),
        ]);

        return new HtmlResponse($html, 500);
    }
}
