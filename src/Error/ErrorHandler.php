<?php declare(strict_types=1);

namespace Ellipse\Http\Error;

use Psr\Http\Message\ServerRequestInterface;

class ErrorHandler
{
    /**
     * The incoming request.
     *
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private $request;

    /**
     * The request handler factory producing a request handler for a given
     * exception.
     *
     * @var callable
     */
    private $factory;

    /**
     * Set up an error handler with the given request and request handler
     * factory
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @param callable                                  $factory
     */
    public function __construct(ServerRequestInterface $request, callable $factory)
    {
        $this->request = $request;
        $this->factory = $factory;
    }

    /**
     * Register the exception handler and the shutdown handler.
     *
     * @return void
     */
    public function register()
    {
        set_exception_handler(new ExceptionHandler($this->request, $this->factory));
        register_shutdown_function(new ShutdownHandler($this->request, $this->factory));
    }
}
