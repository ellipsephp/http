<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Handlers\BootExceptionRequestHandler;
use Ellipse\Http\Exceptions\BootException;

describe('BootExceptionRequestHandler', function () {

    beforeEach(function () {

        $this->exception = mock(Throwable::class)->get();

        $this->handler = new BootExceptionRequestHandler($this->exception);

    });

    it('should implement RequestHandlerInterface', function () {

        expect($this->handler)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class)->get();

        });

        it('should throw the exception wrapped inside a BootException', function () {

            $test = function () {

                $this->handler->handle($this->request);

            };

            $exception = new BootException($this->exception);

            expect($test)->toThrow($exception);

        });

    });

});
