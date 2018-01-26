<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

class DefaultRequestBasedResponseFactory extends RequestBasedResponseFactory
{
    /**
     * Set up a request based response factory with default factories.
     *
     * @param bool $debug
     */
    public function __construct(bool $debug)
    {
        $html = $debug ? new DetailledHtmlResponseFactory : new SimpleHtmlResponseFactory;
        $json = $debug ? new DetailledJsonResponseFactory : new SimpleJsonResponseFactory;

        parent::__construct(['text/html' => $html, 'application/json' => $json]);
    }
}
