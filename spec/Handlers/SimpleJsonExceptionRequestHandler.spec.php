<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Zend\Diactoros\Response;

use Ellipse\Http\Handlers\SimpleJsonExceptionRequestHandler;

describe('SimpleJsonExceptionRequestHandler', function () {

    beforeEach(function () {

        $this->handler = new SimpleJsonExceptionRequestHandler(new Response);

    });

    it('should implement RequestHandlerInterface', function () {

        expect($this->handler)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        it('should return a detailled html response', function () {

            $request = mock(ServerRequestInterface::class)->get();

            $test = $this->handler->handle($request);

            expect($test->getStatusCode())->toEqual(500);
            expect($test->getHeaderLine('Content-type'))->toContain('application/json');
            expect(json_decode($test->getBody(), true))->toContain('Server error');

        });

    });

});
