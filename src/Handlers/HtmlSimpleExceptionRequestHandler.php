<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use League\Plates\Engine;

use Zend\Diactoros\Response\HtmlResponse;

class HtmlSimpleExceptionRequestHandler implements RequestHandlerInterface
{
    /**
     * The plates templating engine used to render the template.
     *
     * @var \League\Plates\Engine
     */
    private $engine;

    /**
     * Set up a html simple server error request handler with the given plates
     * engine.
     *
     * @param \League\Plates\Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Return a html simple response for the exception.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $html = $this->engine->render('simple');

        return new HtmlResponse($html, 500);
    }
}
