<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Psr\Http\Server\RequestHandlerInterface;

use League\Plates\Engine;

use Zend\Diactoros\Response\HtmlResponse;

class DefaultRequestHandler implements RequestHandlerInterface
{
    /**
     * the plates templating engine used to render the templates.
     *
     * @var \League\Plates\Engine
     */
    private $engine;

    /**
     * Set up a detailled html response factory.
     */
    public function __construct()
    {
        $root = realpath(__DIR__ . '/../..');

        $this->engine = new Engine($root . '/templates');
    }

    /**
     * Return a default response when nothing returned a response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $html = $this->engine->render('default');

        return new HtmlResponse($html);
    }
}
