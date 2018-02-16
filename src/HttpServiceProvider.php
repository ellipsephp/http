<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Container\ContainerInterface;

use Psr\Http\Server\RequestHandlerInterface;

use Interop\Container\ServiceProviderInterface;

use Ellipse\DispatcherFactory;
use Ellipse\DispatcherFactoryInterface;
use Ellipse\Http\Handlers\DefaultRequestHandler;

class HttpServiceProvider implements ServiceProviderInterface
{
    public function getFactories()
    {
        return [
            'ellipse.http.kernel' => [$this, 'getHttpKernel'],
            'ellipse.http.middleware' => [$this, 'getMiddleware'],
            'ellipse.http.handler' => [$this, 'getRequestHandler'],
        ];
    }

    public function getExtensions()
    {
        return [
            DispatcherFactoryInterface::class => [$this, 'getDispatcherFactory'],
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
        $middleware = $container->get('ellipse.http.middleware');
        $handler = $container->get('ellipse.http.handler');

        return $factory($handler, $middleware);
    }

    /**
     * Return an empty array as middleware. End user can extend it.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return iterable
     */
    public function getMiddleware(ContainerInterface $container): iterable
    {
        return [];
    }

    /**
     * Return a default request handler. End user can extend it.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Psr\Http\Server\RequestHandlerInterface
     */
    public function getRequestHandler(ContainerInterface $container): RequestHandlerInterface
    {
        return new DefaultRequestHandler;
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
}
