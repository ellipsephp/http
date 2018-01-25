<?php declare(strict_types=1);

namespace Ellipse\Http;

use Throwable;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Exceptions\HttpKernelTypeException;

class HttpKernelFactory
{
    private $delegate;

    public function __construct(callable $delegate)
    {
        $this->delegate = $delegate;
    }

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
