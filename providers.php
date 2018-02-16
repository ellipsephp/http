<?php declare(strict_types=1);

use Ellipse\Http\HttpServiceProvider;
use Ellipse\Providers\ServiceExtensions;

return [

    /**
     * Ellipse http service provider.
     */
    new HttpServiceProvider,

    /**
     * Ellipse http kernel configuration.
     */
    new ServiceExtensions([

        /**
         * The list of Psr-15 middleware used by the ellipse http kernel can be
         * defined here.
         *
         * The default value is an empty array.
         */
         'ellipse.http.middleware' => function ($container, iterable $middleware): iterable {

             return $middleware;

         },

    ]),

];
