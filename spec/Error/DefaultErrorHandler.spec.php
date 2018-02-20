<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Ellipse\Http\Error\ErrorHandler;
use Ellipse\Http\Error\ExceptionHandler;
use Ellipse\Http\Error\ShutdownHandler;
use Ellipse\Http\Error\DefaultErrorHandler;
use Ellipse\Http\Handlers\ExceptionRequestHandlerFactory;

describe('DefaultErrorHandler', function () {

    beforeEach(function () {

        $this->request = mock(ServerRequestInterface::class)->get();
        $this->prototype = mock(ResponseInterface::class)->get();

    });

    it('should extend ErrorHandler', function () {

        $test = new DefaultErrorHandler($this->request, $this->prototype, false);

        expect($test)->toBeAnInstanceOf(ErrorHandler::class);

    });

    describe('->register()', function () {

        beforeEach(function () {

            $this->exception = null;
            $this->shutdown = null;

            allow('error_reporting')->toBeCalled()->andReturn(0);

            allow('set_exception_handler')->toBeCalled()->andRun(function ($handler) {

                $this->exception = $handler;

            });

            allow('register_shutdown_function')->toBeCalled()->andRun(function ($handler) {

                $this->shutdown = $handler;

            });

        });

        context('when the given debug mode is false', function () {

            it('should register handlers using the request and a default exception factory with debug mode set to false', function () {

                $handler = new DefaultErrorHandler($this->request, $this->prototype, false);

                $handler->register();

                $factory = new ExceptionRequestHandlerFactory($this->prototype, false);

                expect($this->exception)->toEqual(new ExceptionHandler($this->request, $factory));
                expect($this->shutdown)->toEqual($test = new ShutdownHandler($this->request, $factory));

            });

        });

        context('when the given debug mode is true', function () {

            it('should register handlers using the request and a default exception factory with debug mode set to true', function () {

                $handler = new DefaultErrorHandler($this->request, $this->prototype, true);

                $handler->register();

                $factory = new ExceptionRequestHandlerFactory($this->prototype, true);

                expect($this->exception)->toEqual(new ExceptionHandler($this->request, $factory));
                expect($this->shutdown)->toEqual($test = new ShutdownHandler($this->request, $factory));

            });

        });

    });

});
