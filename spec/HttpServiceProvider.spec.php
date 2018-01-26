<?php

use Ellipse\Container;
use Ellipse\Dispatcher;
use Ellipse\DispatcherFactory;
use Ellipse\DispatcherFactoryInterface;
use Ellipse\Http\HttpServiceProvider;
use Ellipse\Http\Handlers\DefaultRequestHandler;

describe('HttpServiceProvider', function () {

    beforeEach(function () {

        $this->provider = new HttpServiceProvider;

    });

    describe('->getFactories()', function () {

        it('should return an array', function () {

            $test = $this->provider->getFactories();

            expect($test)->toBeAn('array');

        });

        it('should return an array containing an app.http.kernel', function () {

            $test = $this->provider->getFactories();

            expect($test)->toContainKey('app.http.kernel');

        });

    });

    describe('->getExtensions()', function () {

        it('should return an array', function () {

            $test = $this->provider->getExtensions();

            expect($test)->toBeAn('array');

        });

        it('should return an array containing an DispatcherFactoryInterface::class alias', function () {

            $test = $this->provider->getExtensions();

            expect($test)->toContainKey(DispatcherFactoryInterface::class);

        });

        it('should return an array containing an app.http.middleware', function () {

            $test = $this->provider->getExtensions();

            expect($test)->toContainKey('app.http.middleware');

        });

        it('should return an array containing an app.http.handler', function () {

            $test = $this->provider->getExtensions();

            expect($test)->toContainKey('app.http.handler');

        });

    });

    context('when consumed by a container', function () {

        beforeEach(function () {

            $this->container = new Container([$this->provider]);

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
