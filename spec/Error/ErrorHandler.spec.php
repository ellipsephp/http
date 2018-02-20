<?php

use function Eloquent\Phony\Kahlan\mock;
use function Eloquent\Phony\Kahlan\stub;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Http\Error\ErrorHandler;
use Ellipse\Http\Error\ExceptionHandler;
use Ellipse\Http\Error\ShutdownHandler;

describe('ErrorHandler', function () {

    beforeEach(function () {

        $this->request = mock(ServerRequestInterface::class)->get();
        $this->factory = stub();

        $this->handler = new ErrorHandler($this->request, $this->factory);

    });

    describe('->register()', function () {

        it('should register an ExceptionHandler and a ShutdownHandler using the request and the factory', function () {

            $this->exception = null;
            $this->shutdown = null;

            allow('error_reporting')->toBeCalled()->andReturn(0);

            allow('set_exception_handler')->toBeCalled()->andRun(function ($handler) {

                $this->exception = $handler;

            });

            allow('register_shutdown_function')->toBeCalled()->andRun(function ($handler) {

                $this->shutdown = $handler;

            });

            $this->handler->register();

            expect($this->exception)->toEqual(new ExceptionHandler($this->request, $this->factory));
            expect($this->shutdown)->toEqual($test = new ShutdownHandler($this->request, $this->factory));

        });

    });

});
