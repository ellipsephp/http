<?php

use function Eloquent\Phony\Kahlan\mock;

use Negotiation\Negotiator;

use Ellipse\Http\Handlers\ExceptionRequestHandler;
use Ellipse\Http\Handlers\ExceptionRequestHandlerFactory;

describe('ExceptionRequestHandlerFactory', function () {

    beforeEach(function () {

        $this->exception = mock(Throwable::class)->get();
        $this->negotiator = new Negotiator;

    });

    describe('->__invoke()', function () {

        context('when debug option is set to false', function () {

            it('should return an exception request handler with debug value set to false', function () {

                $factory = new ExceptionRequestHandlerFactory(false);

                $test = $factory($this->exception);

                $handler = new ExceptionRequestHandler($this->exception, $this->negotiator, false);

                expect($test)->toEqual($handler);

            });

        });

        context('when debug option is set to true', function () {

            it('should return an exception request handler with debug value set to true', function () {

                $factory = new ExceptionRequestHandlerFactory(true);

                $test = $factory($this->exception);

                $handler = new ExceptionRequestHandler($this->exception, $this->negotiator, true);

                expect($test)->toEqual($handler);

            });

        });

    });

});
