<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Message\ResponseInterface;

class ExceptionRequestHandlerFactory
{
    /**
     * The response prototype.
     *
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $prototype;

    /**
     * Whether the application is in debug mode or not.
     *
     * @var bool
     */
    private $debug;

    /**
     * Set up an exeception request handler factory with the given response
     * prototype and debug mode.
     *
     * @param \Psr\Http\Message\ResponseInterface   $prototype
     * @param bool                                  $debug
     */
    public function __construct(ResponseInterface $prototype, bool $debug)
    {
        $this->prototype = $prototype;
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
        return new ExceptionRequestHandler($e, $this->prototype, $this->debug);
    }
}
