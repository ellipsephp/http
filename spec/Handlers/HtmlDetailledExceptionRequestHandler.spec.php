<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use League\Plates\Engine;

use Ellipse\Http\Exceptions\Inspector;
use Ellipse\Http\Handlers\HtmlDetailledExceptionRequestHandler;

describe('HtmlDetailledExceptionRequestHandler', function () {

    beforeEach(function () {

        $this->engine = mock(Engine::class);
        $this->exception = mock(Throwable::class)->get();

        $this->handler = new HtmlDetailledExceptionRequestHandler($this->engine->get(), $this->exception);

    });

    it('should implement RequestHandlerInterface', function () {

        expect($this->handler)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class)->get();

        });

        it('should return a detailled html response', function () {

            $details = new Inspector($this->exception);

            $this->engine->render->with('detailled', ['details' => $details])->returns('contents');

            $test = $this->handler->handle($this->request);

            expect($test->getStatusCode())->toEqual(500);
            expect($test->getHeaderLine('Content-type'))->toContain('text/html');
            expect((string) $test->getBody())->toContain('contents');

        });

    });

});
