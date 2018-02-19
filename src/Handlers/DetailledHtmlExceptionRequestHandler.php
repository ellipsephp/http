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
     * The plates templating engine used to render the template.
     *
     * @var \League\Plates\Engine
     */
    private $engine;

    /**
     * Set up a detailled html exception request handler with the given plates
     * engine and exception.
     *
     * @param \League\Plates\Engine $engine
     * @param \Throwable            $e
     */
    public function __construct(Engine $engine, Throwable $e)
    {
        $this->engine = $engine;
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
        $html = $this->engine->render('detailled', [
            'details' => new Inspector($this->e),
        ]);

        return new HtmlResponse($html, 500);
    }
}
