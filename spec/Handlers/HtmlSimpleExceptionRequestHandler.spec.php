<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use League\Plates\Engine;

use Ellipse\Http\Handlers\HtmlSimpleExceptionRequestHandler;

describe('HtmlSimpleExceptionRequestHandler', function () {

    beforeEach(function () {

        $this->engine = mock(Engine::class);

        $this->handler = new HtmlSimpleExceptionRequestHandler($this->engine->get());

    });

    it('should implement RequestHandlerInterface', function () {

        expect($this->handler)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class)->get();

        });

        it('should return a simple html response', function () {

            $this->engine->render->with('simple')->returns('contents');

            $test = $this->handler->handle($this->request);

            expect($test->getStatusCode())->toEqual(500);
            expect($test->getHeaderLine('Content-type'))->toContain('text/html');
            expect((string) $test->getBody())->toContain('contents');

        });

    });

});
