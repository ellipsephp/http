<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Handlers\DefaultRequestHandler;

describe('DefaultRequestHandler', function () {

    beforeEach(function () {

        $this->handler = new DefaultRequestHandler;

    });

    it('should implement RequestHandlerInterface', function () {

        expect($this->handler)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class)->get();

        });

        it('should return an html response', function () {

            $test = $this->handler->handle($this->request);

            expect($test->getStatusCode())->toEqual(200);
            expect($test->getHeaderLine('Content-type'))->toContain('text/html');
            expect((string) $test->getBody())->toContain('Welcome to ellipse!');

        });

    });

});
