<?php declare(strict_types=1);

namespace Ellipse\Http;

use Throwable;
use ErrorException;

use function Http\Response\send;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Http\Exceptions\FatalException;
use Ellipse\Http\Exceptions\Response\RequestBasedResponseFactory;

class ShutdownHandler
{
    /**
     * The error code of the errors to report.
     *
     * @var int
     */
    private $report;

    /**
     * The incoming request.
     *
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private $request;

    /**
     * The response factory used to render a page for the error.
     *
     * @var \Ellipse\Http\Exceptions\Response\RequestBasedResponseFactory
     */
    private $factory;

    /**
     * Set up a shutdown handler with the given request and response factory.
     *
     * Set the error to report as the intersection between the error which can
     * be reported by this object and the configured error reporting value.
     *
     * Then update the error reporting value so the error set to be reported by
     * this object at the previous step are silenced by php.
     *
     * @param \Psr\Http\Message\ServerRequestInterface                      $request
     * @param \Ellipse\Http\Exceptions\Response\RequestBasedResponseFactory $factory
     */
    public function __construct(ServerRequestInterface $request, RequestBasedResponseFactory $factory)
    {
        $this->request = $request;
        $this->factory = $factory;

        $config = error_reporting();

        $fatal = E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING;

        $this->report = $config & $fatal;

        error_reporting($config & ~ $fatal);
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

                $response = $this->factory->response($this->request, $e);

                send($response);

            }

        }

    }
}
