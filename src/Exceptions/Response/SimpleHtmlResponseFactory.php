<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

use Throwable;

use Psr\Http\Message\ResponseInterface;

use League\Plates\Engine;

use Zend\Diactoros\Response\HtmlResponse;

class SimpleHtmlResponseFactory
{
    private $engine;

    public function __construct()
    {
        $this->engine = new Engine(__DIR__ . '/templates');
    }

    public function response(Throwable $e): ResponseInterface
    {
        $html = $this->engine->render('simple');

        return new HtmlResponse($html, 500);
    }
}
