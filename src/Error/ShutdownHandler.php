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
    CONST REPORTABLE = E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING;

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
     * The error code of the errors to report.
     *
     * @var int
     */
    public $report;

    /**
     * Set up a shutdown handler with the given request and request handler
     * factory.
     *
     * Set the error to report as the intersection between the error which can
     * be reported by this object and the configured error reporting value.
     *
     * Then update the error reporting value so the error set to be reported by
     * this object at the previous step are silenced by php.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @param callable                                  $factory
     */
    public function __construct(ServerRequestInterface $request, callable $factory)
    {
        $config = error_reporting();

        error_reporting($config & ~ self::REPORTABLE);

        $this->request = $request;
        $this->factory = $factory;
        $this->report = $config & self::REPORTABLE;
    }

    /**
     * When there is any last error with a type set to be reported by this
     * object, use the reponse factory to produce a response and send it.
     */
    public function __invoke()
    {
        $error = error_get_last();

        if ($error) {

            if (($error['type'] & $this->report) > 0) {

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
    }
}
