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
     * with boot failure. This way an http kernel is always returned.
     *
     * @param string    $env
     * @param bool      $debug
     * @param string    $root
     * @return \Ellipse\Http\AbstractHttpKernel
     */
    public function __invoke(string $env, bool $debug, string $root): AbstractHttpKernel
    {
        // get to get the http kernel from the delegate
        try {

            $kernel = ($this->delegate)($env, $debug, $root);

            if ($kernel instanceof RequestHandlerInterface) {

                return new HttpKernel($kernel, $debug);

            }

            throw new HttpKernelTypeException($kernel);

        }

        // display the boot error when something goes wrong
        catch (Throwable $e) {

            return new HttpKernelWithBootFailure($e, $debug);

        }
    }
}
