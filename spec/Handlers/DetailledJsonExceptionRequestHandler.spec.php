<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Handlers\DetailledJsonExceptionRequestHandler;

describe('DetailledJsonExceptionRequestHandler', function () {

    beforeEach(function () {

        $this->exception = mock(Throwable::class)->get();

        $this->handler = new DetailledJsonExceptionRequestHandler($this->exception);

    });

    it('should implement RequestHandlerInterface', function () {

        expect($this->handler)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class)->get();

        });

        it('should return a detailled html response', function () {

            $test = $this->handler->handle($this->request);

            expect($test->getStatusCode())->toEqual(500);
            expect($test->getHeaderLine('Content-type'))->toContain('application/json');
            expect(json_decode($test->getBody(), true))->toContain(get_class($this->exception));

        });

    });

});
