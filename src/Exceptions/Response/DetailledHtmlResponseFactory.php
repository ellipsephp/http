<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

use Throwable;

use Psr\Http\Message\ResponseInterface;

use League\Plates\Engine;

use Zend\Diactoros\Response\HtmlResponse;

use Ellipse\Http\Exceptions\Inspector;

class DetailledHtmlResponseFactory
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
        $this->engine = new Engine(__DIR__ . '/templates');
    }

    /**
     * Return a detailled html response for the given exception.
     *
     * @param \Throwable $e
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response(Throwable $e): ResponseInterface
    {
        $html = $this->engine->render('detailled', [
            'details' => new Inspector($e),
        ]);

        return new HtmlResponse($html, 500);
    }
}
