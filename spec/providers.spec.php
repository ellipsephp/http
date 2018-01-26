<?php

use Ellipse\Container;
use Ellipse\Dispatcher;
use Ellipse\DispatcherFactory;
use Ellipse\DispatcherFactoryInterface;
use Ellipse\Http\Handlers\DefaultRequestHandler;

describe('providers.php', function () {

    context('when consumed by a container', function () {

        beforeEach(function () {

            $providers = require __DIR__ . '/../providers.php';

            $this->container = new Container($providers);

        });

        it('should provide an instance of Dispatcher for the app.http.kernel alias', function () {

            $test = $this->container->get('app.http.kernel');

            expect($test)->toBeAnInstanceOf(Dispatcher::class);

        });

        it('should provide an instance of DispatcherFactory for the DispatcherFactoryInterface::class alias', function () {

            $test = $this->container->get(DispatcherFactoryInterface::class);

            expect($test)->toBeAnInstanceOf(DispatcherFactory::class);

        });

        it('should provide an empty array for the app.http.middleware alias', function () {

            $test = $this->container->get('app.http.middleware');

            expect($test)->toEqual([]);

        });

        it('should provide an instance of DefaultRequestHandler for the app.http.handler alias', function () {

            $test = $this->container->get('app.http.handler');

            expect($test)->toBeAnInstanceOf(DefaultRequestHandler::class);

        });

    });

});
