<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

use Throwable;

use Psr\Http\Message\ResponseInterface;

use League\Plates\Engine;

use Zend\Diactoros\Response\HtmlResponse;

class SimpleHtmlResponseFactory
{
    /**
     * the plates templating engine used to render the templates.
     *
     * @var \League\Plates\Engine
     */
    private $engine;

    /**
     * Set up a simple html response factory.
     */
    public function __construct()
    {
        $root = realpath(__DIR__ . '/../../..');

        $this->engine = new Engine($root . '/templates');
    }

    /**
     * Return a simple html response for the given exception.
     *
     * @param \Throwable $e
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response(Throwable $e): ResponseInterface
    {
        $html = $this->engine->render('simple');

        return new HtmlResponse($html, 500);
    }
}
