<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Interop\Http\Factory\ResponseFactoryInterface;

class ExceptionRequestHandler extends RequestBasedRequestHandler
{
    /**
     * Set up an exception request handler with the given exception, response
     * factory and debug mode.
     *
     * @param \Throwable                                        $e
     * @param \Interop\Http\Factory\ResponseFactoryInterface    $factory
     * @param bool                                              $debug
     */
    public function __construct(Throwable $e, ResponseFactoryInterface $factory, bool $debug)
    {
        parent::__construct([
            'text/html' => $debug
                ? new DetailledHtmlExceptionRequestHandler($e, $factory)
                : new SimpleHtmlExceptionRequestHandler($factory),
            'application/json' => $debug
                ? new DetailledJsonExceptionRequestHandler($e, $factory)
                : new SimpleJsonExceptionRequestHandler($factory),
        ]);
    }
}
