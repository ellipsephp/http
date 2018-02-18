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
     * The delegace actually building the request handler used as http kernel.
     *
     * @var callable
     */
    private $delegate;

    /**
     * Set up a http kernel factory with the given delegate.
     *
     * @param callable $delegate
     */
    public function __construct(callable $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * Proxy the delegate and wrap the produced request handler inside a http
     * kernel. When an exception is thrown by the deletate, return a http kernel
     * with boot failure so an http kernel is always returned.
     *
     * @param string    $env
     * @param bool      $debug
     * @return \Ellipse\Http\HttpKernel
     */
    public function __invoke(string $env, bool $debug): HttpKernel
    {
        try {

            $kernel = ($this->delegate)($env, $debug);

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
