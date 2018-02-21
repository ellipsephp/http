<?php declare(strict_types=1);

namespace Ellipse\Http\Error;

use Throwable;

use function Http\Response\send;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Http\Exceptions\UncaughtException;

class ExceptionHandler
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
     * Set up an exception handler with the given request and request handler
     * factory.
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
     * Use the reponse factory to produce a response and send it.
     *
     * @param \Throwable $e
     * @return bool
     */
    public function __invoke(Throwable $e): bool
    {
        $e = new UncaughtException($e);

        $response = ($this->factory)($e)->handle($this->request);

        send($response);

        return true;
    }
}
