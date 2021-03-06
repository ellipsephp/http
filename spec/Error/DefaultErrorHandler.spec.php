<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;

use Interop\Http\Factory\ResponseFactoryInterface;

use Ellipse\Http\Error\ErrorHandler;
use Ellipse\Http\Error\ExceptionHandler;
use Ellipse\Http\Error\ShutdownHandler;
use Ellipse\Http\Error\DefaultErrorHandler;
use Ellipse\Http\Handlers\ExceptionRequestHandlerFactory;

describe('DefaultErrorHandler', function () {

    beforeEach(function () {

        $this->request = mock(ServerRequestInterface::class)->get();
        $this->factory = mock(ResponseFactoryInterface::class)->get();

    });

    it('should extend ErrorHandler', function () {

        $test = new DefaultErrorHandler($this->request, $this->factory, false);

        expect($test)->toBeAnInstanceOf(ErrorHandler::class);

    });

    describe('->register()', function () {

        beforeEach(function () {

            allow('error_reporting')->toBeCalled()->andReturn(E_ALL);

            allow('set_exception_handler')->toBeCalled()->andRun(function ($handler) {

                $this->exception = $handler;

            });

            allow('register_shutdown_function')->toBeCalled()->andRun(function ($handler) {

                $this->shutdown = $handler;

            });

        });

        context('when the given debug mode is false', function () {

            it('should register handlers using the request and a default exception factory with debug mode set to false', function () {

                $handler = new DefaultErrorHandler($this->request, $this->factory, false);

                $handler->register();

                $factory = new ExceptionRequestHandlerFactory($this->factory, false);

                expect($this->exception)->toEqual(new ExceptionHandler($this->request, $factory));
                expect($this->shutdown)->toEqual(new ShutdownHandler($this->request, $factory));

            });

        });

        context('when the given debug mode is true', function () {

            it('should register handlers using the request and a default exception factory with debug mode set to true', function () {

                $handler = new DefaultErrorHandler($this->request, $this->factory, true);

                $handler->register();

                $factory = new ExceptionRequestHandlerFactory($this->factory, true);

                expect($this->exception)->toEqual(new ExceptionHandler($this->request, $factory));
                expect($this->shutdown)->toEqual(new ShutdownHandler($this->request, $factory));

            });

        });

    });

});
