<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Negotiation\Negotiator;
use League\Plates\Engine;

class ExceptionRequestHandler extends RequestBasedRequestHandler
{
    /**
     * Set up an exception request handler with the given exception, content
     * type negotiator and debug status.
     *
     * @param \Throwable                $e
     * @param \Negotiation\Negotiator   $negotiator
     * @param bool                      $debug
     */
    public function __construct(Throwable $e, Negotiator $negotiator, bool $debug)
    {
        parent::__construct($negotiator, [
            'text/html' => $debug
                ? new DetailledHtmlExceptionRequestHandler($e)
                : new SimpleHtmlExceptionRequestHandler,
            'application/json' => $debug
                ? new DetailledJsonExceptionRequestHandler($e)
                : new SimpleJsonExceptionRequestHandler,
        ]);
    }
}
