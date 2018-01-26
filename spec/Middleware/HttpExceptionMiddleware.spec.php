<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Middleware\HttpExceptionMiddleware;
use Ellipse\Http\Exceptions\HttpException;

describe('HttpExceptionMiddleware', function () {

    beforeEach(function () {

        $this->middleware = new HttpExceptionMiddleware;

    });

    it('should implement MiddlewareInterface', function () {

        expect($this->middleware)->toBeAnInstanceOf(MiddlewareInterface::class);

    });

    describe('->process()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class)->get();
            $this->response = mock(ResponseInterface::class)->get();
            $this->handler = mock(RequestHandlerInterface::class);

        });

        context('when the handler ->handle() method does not throw an exception', function () {

            it('should proxy the handler', function () {

                $this->handler->handle->with($this->request)->returns($this->response);

                $test = $this->middleware->process($this->request, $this->handler->get());

                expect($test)->toBe($this->response);

            });

        });

        context('when the handler ->handle() method throws an exception', function () {

            it('should propagate the exception wrapped inside a HttpException', function () {

                $exception = new Exception;

                $this->handler->handle->with($this->request)->throws($exception);

                $test = function () {

                    $this->middleware->process($this->request, $this->handler->get());

                };

                $exception = new HttpException($exception);

                expect($test)->toThrow($exception);

            });

        });

    });

});
