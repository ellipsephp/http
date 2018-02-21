<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Interop\Http\Factory\ResponseFactoryInterface;

class ExceptionRequestHandlerFactory
{
    /**
     * The response factory.
     *
     * @var \Interop\Http\Factory\ResponseFactoryInterface
     */
    private $factory;

    /**
     * Whether the application is in debug mode or not.
     *
     * @var bool
     */
    private $debug;

    /**
     * Set up an exeception request handler factory with the given response
     * factory and debug mode.
     *
     * @param \Interop\Http\Factory\ResponseFactoryInterface    $factory
     * @param bool                                              $debug
     */
    public function __construct(ResponseFactoryInterface $factory, bool $debug)
    {
        $this->factory = $factory;
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
        return new ExceptionRequestHandler($e, $this->factory, $this->debug);
    }
}
