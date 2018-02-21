<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Http\Factory\Diactoros\ResponseFactory;

use Ellipse\Http\Handlers\SimpleHtmlExceptionRequestHandler;

describe('SimpleHtmlExceptionRequestHandler', function () {

    beforeEach(function () {

        $this->handler = new SimpleHtmlExceptionRequestHandler(new ResponseFactory);

    });

    it('should implement RequestHandlerInterface', function () {

        expect($this->handler)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        it('should return a simple html response', function () {

            $request = mock(ServerRequestInterface::class)->get();

            $test = $this->handler->handle($request);

            expect($test->getStatusCode())->toEqual(500);
            expect($test->getHeaderLine('Content-type'))->toContain('text/html');
            expect((string) $test->getBody())->toContain('Server error');

        });

    });

});
