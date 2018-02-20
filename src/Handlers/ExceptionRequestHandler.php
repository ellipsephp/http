<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Message\ResponseInterface;

use Negotiation\Negotiator;

class ExceptionRequestHandler extends RequestBasedRequestHandler
{
    /**
     * Set up an exception request handler with the given exception, content
     * type negotiator and debug status.
     *
     * @param \Throwable                            $e
     * @param \Negotiation\Negotiator               $negotiator
     * @param \Psr\Http\Message\ResponseInterface   $prototype
     * @param bool                                  $debug
     */
    public function __construct(Throwable $e, Negotiator $negotiator, ResponseInterface $prototype, bool $debug)
    {
        parent::__construct($negotiator, [
            'text/html' => $debug
                ? new DetailledHtmlExceptionRequestHandler($e, $prototype)
                : new SimpleHtmlExceptionRequestHandler($prototype),
            'application/json' => $debug
                ? new DetailledJsonExceptionRequestHandler($e, $prototype)
                : new SimpleJsonExceptionRequestHandler($prototype),
        ]);
    }
}
