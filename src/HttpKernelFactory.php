<?php declare(strict_types=1);

namespace Ellipse\Http;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Exceptions\HttpKernelTypeException;

class HttpKernelFactory
{
    /**
     * The bootstrap callable returning the application request handler.
     *
     * @var callable
     */
    private $bootstrap;

    /**
     * Set up a http kernel factory with the given bootstrap callable.
     *
     * @param callable $bootstrap
     */
    public function __construct(callable $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    /**
     * Wrap a http kernel around the request handler returned by the bootstrap
     * callable. When an exception is thrown by the bootstrap callable, return a
     * http kernel with boot failure wrapped around it so a http kernel is
     * always returned.
     *
     * @param string    $env
     * @param bool      $debug
     * @return \Ellipse\Http\HttpKernel
     */
    public function __invoke(string $env, bool $debug): HttpKernel
    {
        try {

            $kernel = ($this->bootstrap)($env, $debug);

            if ($kernel instanceof RequestHandlerInterface) {

                return new HttpKernelWithoutBootFailure($kernel, $debug);

            }

            throw new HttpKernelTypeException($kernel);

        }

        catch (Throwable $e) {

            return new HttpKernelWithBootFailure($e, $debug);

        }
    }
}
