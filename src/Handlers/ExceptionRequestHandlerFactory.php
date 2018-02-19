<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Negotiation\Negotiator;
use League\Plates\Engine;

class ExceptionRequestHandlerFactory
{
    /**
     * Whether the application is in debug mode or not.
     *
     * @var bool
     */
    private $debug;

    /**
     * Set up an exeception request handler factory with the given debug status.
     *
     * @param bool $debug
     */
    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    /**
     * Return an exception request handler producing a response for the given
     * exception.
     *
     * @param \Throwable $e
     * @return \Ellipse\Http\Handlers\ExceptionRequestHandler;
     */
    public function __invoke(Throwable $e): ExceptionRequestHandler
    {
        $negotiator = new Negotiator;

        return new ExceptionRequestHandler($e, $negotiator, $this->debug);
    }
}
