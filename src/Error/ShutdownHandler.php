<?php declare(strict_types=1);

namespace Ellipse\Http\Error;

use Throwable;
use ErrorException;

use function Http\Response\send;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Http\Exceptions\FatalException;

class ShutdownHandler
{
    /**
     * Code of errors which can be reported by this handler.
     *
     * @var int
     */
    CONST FATAL = E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING;

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
     * The value of the error reporting directive.
     *
     * @var int
     */
    private $report;

    /**
     * Set up a shutdown handler with the given request and request handler
     * factory.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @param callable                                  $factory
     */
    public function __construct(ServerRequestInterface $request, callable $factory)
    {
        $this->request = $request;
        $this->factory = $factory;
        $this->report = error_reporting();

        // do not let php report error handled by this handler.
        error_reporting($this->report & ~ self::FATAL);
    }

    /**
     * When there is any last error which should be handled by this handler, use
     * the reponse factory to produce a response and send it.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $error = error_get_last();

        if (! is_null($error) && $this->shouldHandle($error)) {

            $e = new FatalException(
                new ErrorException(
                    $error['message'],
                    0,
                    $error['type'],
                    $error['file'],
                    $error['line']
                )
            );

            $response = ($this->factory)($e)->handle($this->request);

            send($response);

        }
    }

    /**
     * Return wether the given error should be reported and can be handled by
     * this handler.
     *
     * @param array $error
     * @return bool
     */
    private function shouldHandle(array $error): bool
    {
        return ($error['type'] & $this->report & self::FATAL) > 0;
    }
}
