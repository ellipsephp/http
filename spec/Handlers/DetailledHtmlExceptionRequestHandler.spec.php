<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Zend\Diactoros\Response;

use Ellipse\Http\Exceptions\Inspector;
use Ellipse\Http\Handlers\DetailledHtmlExceptionRequestHandler;

describe('DetailledHtmlExceptionRequestHandler', function () {

    beforeEach(function () {

        $this->exception = mock(Throwable::class)->get();

        $this->handler = new DetailledHtmlExceptionRequestHandler($this->exception, new Response);

    });

    it('should implement RequestHandlerInterface', function () {

        expect($this->handler)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        it('should return a detailled html response', function () {

            $request = mock(ServerRequestInterface::class)->get();

            $test = $this->handler->handle($request);

            expect($test->getStatusCode())->toEqual(500);
            expect($test->getHeaderLine('Content-type'))->toContain('text/html');
            expect((string) $test->getBody())->toContain(get_class($this->exception));

        });

    });

});
