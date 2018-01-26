<?php declare(strict_types=1);

use Psr\Container\ContainerInterface;

use Ellipse\Dispatcher;
use Ellipse\Http\HttpServiceProvider;
use Ellipse\Providers\ServiceFactories;
use Ellipse\Providers\ServiceExtensions;

return [

    /**
     * This package service provider.
     */
    new HttpServiceProvider,

    /**
     * User defined service factories.
     */
    new ServiceFactories([

        /**
         * This array are the middleware used when building the http kernel.
         */
         'app.http.middleware' => function (ContainerInterface $container) {

             return [];

         },

    ]),

    /**
     * User defined service extensions.
     */
    new ServiceExtensions([

        /**
         * Here the http kernel can be extended.
         *
         * It is a dispatcher so middleware can be wrapped around it by using
         * $kernel->with(new SomeMiddleware).
         */
        'app.http.kernel' => function (ContainerInterface $container, Dispatcher $kernel) {

            return $kernel;

        },

    ]),

];
