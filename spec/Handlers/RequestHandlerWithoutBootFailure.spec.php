<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Handlers\RequestHandlerWithoutBootFailure;
use Ellipse\Http\Exceptions\HttpException;

describe('RequestHandlerWithoutBootFailure', function () {

    beforeEach(function () {

        $this->delegate = mock(RequestHandlerInterface::class);

        $this->handler = new RequestHandlerWithoutBootFailure($this->delegate->get());

    });

    it('should implement RequestHandlerInterface', function () {

        expect($this->handler)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class)->get();

        });

        context('when the delegate does not throw an exception', function () {

            it('should proxy the delegate', function () {

                $response = mock(ResponseInterface::class)->get();

                $this->delegate->handle->with($this->request)->returns($response);

                $test = $this->handler->handle($this->request);

                expect($test)->toBe($response);

            });

        });

        context('when the delegate throws an exception', function () {

            it('should throw the exception wrapped inside a HttpException', function () {

                $exception = mock(Throwable::class)->get();

                $this->delegate->handle->with($this->request)->throws($exception);

                $test = function () { $this->handler->handle($this->request); };

                $exception = new HttpException($exception);

                expect($test)->toThrow($exception);

            });

        });

    });

});
