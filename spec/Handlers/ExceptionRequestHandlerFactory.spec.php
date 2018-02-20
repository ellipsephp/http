<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ResponseInterface;

use Ellipse\Http\Handlers\ExceptionRequestHandler;
use Ellipse\Http\Handlers\ExceptionRequestHandlerFactory;

describe('ExceptionRequestHandlerFactory', function () {

    beforeEach(function () {

        $this->exception = mock(Throwable::class)->get();
        $this->prototype = mock(ResponseInterface::class)->get();

    });

    describe('->__invoke()', function () {

        context('when debug option is set to false', function () {

            it('should return an exception request handler with debug value set to false', function () {

                $factory = new ExceptionRequestHandlerFactory($this->prototype, false);

                $test = $factory($this->exception);

                $handler = new ExceptionRequestHandler($this->exception, $this->prototype, false);

                expect($test)->toEqual($handler);

            });

        });

        context('when debug option is set to true', function () {

            it('should return an exception request handler with debug value set to true', function () {

                $factory = new ExceptionRequestHandlerFactory($this->prototype, true);

                $test = $factory($this->exception);

                $handler = new ExceptionRequestHandler($this->exception, $this->prototype, true);

                expect($test)->toEqual($handler);

            });

        });

    });

});
