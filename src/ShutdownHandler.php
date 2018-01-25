<?php declare(strict_types=1);

namespace Ellipse\Http;

use Throwable;
use ErrorException;

use function Http\Response\send;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Http\Exceptions\UnrecoverableException;
use Ellipse\Http\Exceptions\Response\RequestBasedResponseFactory;

class ShutdownHandler
{
    private $report;
    private $request;
    private $factory;

    public function __construct(ServerRequestInterface $request, RequestBasedResponseFactory $factory)
    {
        $this->request = $request;
        $this->factory = $factory;

        // This handler report non recoverable error which are set to be
        // reported by php config
        $config = error_reporting();

        $nonrecoverable = E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING;

        $this->report = $config & $nonrecoverable;

        // error reported by this handler are silenced
        error_reporting($config & ~ $nonrecoverable);
    }

    public function __invoke()
    {
        $error = error_get_last();

        if ($error) {

            if ($error['type'] & $this->report > 0) {

                $e = new UnrecoverableException(
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
