<?php

use function Eloquent\Phony\Kahlan\mock;

use Negotiation\Negotiator;
use League\Plates\Engine;

use Ellipse\Dispatcher\RequestHandlerWithMiddleware;
use Ellipse\Http\Middleware\JsonHandlerMiddleware;
use Ellipse\Http\Handlers\ExceptionRequestHandlerFactory;
use Ellipse\Http\Handlers\HtmlSimpleExceptionRequestHandler;
use Ellipse\Http\Handlers\JsonSimpleExceptionRequestHandler;
use Ellipse\Http\Handlers\HtmlDetailledExceptionRequestHandler;
use Ellipse\Http\Handlers\JsonDetailledExceptionRequestHandler;

describe('ExceptionRequestHandlerFactory', function () {

    beforeEach(function () {

        $this->negotiator = new Negotiator;
        $this->priorities = ['text/html', 'application/json'];
        $this->engine = new Engine('templates');
        $this->exception = mock(Throwable::class)->get();

    });

    describe('->__invoke()', function () {

        context('when the debug value is set to false', function () {

            it('should return a request handler producing simple response', function () {

                $factory = new ExceptionRequestHandlerFactory('templates', false);

                $test = $factory($this->exception);

                $handler = new RequestHandlerWithMiddleware(
                    new HtmlSimpleExceptionRequestHandler($this->engine),
                    new JsonHandlerMiddleware(
                        $this->negotiator,
                        $this->priorities,
                        new JsonSimpleExceptionRequestHandler
                    )
                );

                expect($test)->toEqual($handler);

            });

        });

        context('when the debug value is set to true', function () {

            it('should return a request handler producing detailled response', function () {

                $factory = new ExceptionRequestHandlerFactory('templates', true);

                $test = $factory($this->exception);

                $handler = new RequestHandlerWithMiddleware(
                    new HtmlDetailledExceptionRequestHandler($this->engine, $this->exception),
                    new JsonHandlerMiddleware(
                        $this->negotiator,
                        $this->priorities,
                        new JsonDetailledExceptionRequestHandler($this->exception)
                    )
                );

                expect($test)->toEqual($handler);

            });

        });

    });

});
