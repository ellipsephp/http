<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class DefaultRequestBasedResponseFactory extends RequestBasedResponseFactory
{
    public function __construct(bool $debug)
    {
        $html = $debug ? new DetailledHtmlResponseFactory : new SimpleHtmlResponseFactory;
        $json = $debug ? new DetailledJsonResponseFactory : new SimpleJsonResponseFactory;

        parent::__construct(['text/html' => $html, 'application/json' => $json]);
    }
}
