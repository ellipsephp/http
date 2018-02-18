<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Exceptions\ExceptionHandlerMiddleware;
use Ellipse\Http\Middleware\ServerErrorMiddleware;

describe('ServerErrorMiddleware', function () {

    beforeEach(function () {

        $this->middleware = new ServerErrorMiddleware('templates', false);

    });

    it('should extend ExceptionHandlerMiddleware', function () {

        expect($this->middleware)->toBeAnInstanceOf(ExceptionHandlerMiddleware::class);

    });

    describe('->process()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class);
            $this->response = mock(ResponseInterface::class)->get();
            $this->handler = mock(RequestHandlerInterface::class);

        });

        context('when the handler ->handle() method does not throw an exception', function () {

            it('should proxy the handler', function () {

                $this->handler->handle->with($this->request)->returns($this->response);

                $test = $this->middleware->process($this->request->get(), $this->handler->get());

                expect($test)->toBe($this->response);

            });

        });

        context('when the handler ->handle() method throws an exception', function () {

            it('should return a response with 500 status code', function () {

                $exception = mock(Throwable::class)->get();

                $this->request->getServerParams->returns([]);
                $this->request->getHeaderLine->returns('*/*');

                $this->handler->handle->with($this->request)->throws($exception);

                $test = $this->middleware->process($this->request->get(), $this->handler->get());

                expect($test->getStatusCode())->toEqual(500);

            });

        });

    });

});
