<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Container\ContainerInterface;

use Psr\Http\Server\RequestHandlerInterface;

use Interop\Container\ServiceProviderInterface;

use Ellipse\DispatcherFactory;
use Ellipse\DispatcherFactoryInterface;
use Ellipse\Http\Handlers\DefaultHttpRequestHandler;

class HttpServiceProvider implements ServiceProviderInterface
{
    public function getFactories()
    {
        return [
            'app.http.kernel' => [$this, 'getHttpKernel'],
        ];
    }

    public function getExtensions()
    {
        return [
            DispatcherFactoryInterface::class => [$this, 'getDispatcherFactory'],
            'app.http.middleware' => [$this, 'getMiddleware'],
            'app.http.handler' => [$this, 'getRequestHandler'],
        ];
    }

    /**
     * Return the http kernel (a Psr-15 request handler) from the defined
     * middleware and request handler.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Psr\Http\Server\RequestHandlerInterface
     */
    public function getHttpKernel(ContainerInterface $container): RequestHandlerInterface
    {
        $factory = $container->get(DispatcherFactoryInterface::class);
        $middleware = $container->get('app.http.middleware');
        $handler = $container->get('app.http.handler');

        return $factory($handler, $middleware);
    }

    /**
     * Return a default dispatcher factory when none defined.
     *
     * @param \Psr\Container\ContainerInterface     $container
     * @param \Ellipse\DispatcherFactoryInterface   $factory
     * @return \Ellipse\DispatcherFactoryInterface
     */
    public function getDispatcherFactory(ContainerInterface $container, DispatcherFactoryInterface $factory = null): DispatcherFactoryInterface
    {
        if (is_null($factory)) {

            return new DispatcherFactory;

        }

        return $factory;
    }

    /**
     * Return an empty array as middleware when none defined.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param iterable                          $middleware
     * @return iterable
     */
    public function getMiddleware(ContainerInterface $container, iterable $middleware = []): iterable
    {
        return $middleware;
     }

    /**
     * Return a default request handler when none is defined.
     *
     * @param \Psr\Container\ContainerInterface         $container
     * @param \Psr\Http\Server\RequestHandlerInterface  $handler
     * @return \Psr\Http\Server\RequestHandlerInterface
     */
    public function getRequestHandler(ContainerInterface $container, RequestHandlerInterface $handler = null): RequestHandlerInterface
    {
        if (is_null($handler)) {

            return new DefaultHttpRequestHandler;

        }

        return $handler;
     }
}
