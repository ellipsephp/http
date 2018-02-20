<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Message\ResponseInterface;

class ExceptionRequestHandler extends RequestBasedRequestHandler
{
    /**
     * Set up an exception request handler with the given exception, response
     * prototype and debug mode.
     *
     * @param \Throwable                            $e
     * @param \Psr\Http\Message\ResponseInterface   $prototype
     * @param bool                                  $debug
     */
    public function __construct(Throwable $e, ResponseInterface $prototype, bool $debug)
    {
        parent::__construct([
            'text/html' => $debug
                ? new DetailledHtmlExceptionRequestHandler($e, $prototype)
                : new SimpleHtmlExceptionRequestHandler($prototype),
            'application/json' => $debug
                ? new DetailledJsonExceptionRequestHandler($e, $prototype)
                : new SimpleJsonExceptionRequestHandler($prototype),
        ]);
    }
}
