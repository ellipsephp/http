<?php

use function Eloquent\Phony\Kahlan\mock;
use function Eloquent\Phony\Kahlan\stub;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Zend\Diactoros\Response\TextResponse;

use Ellipse\Http\Error\ExceptionHandler;
use Ellipse\Http\Exceptions\UncaughtException;

describe('ExceptionHandler', function () {

    beforeEach(function () {

        $this->request = mock(ServerRequestInterface::class)->get();
        $this->factory = stub();

        $this->handler = new ExceptionHandler($this->request, $this->factory);

    });

    describe('->__invoke()', function () {

        beforeEach(function () {

            $this->exception = mock(Throwable::class)->get();

        });

        it('should wrap the exception inside an UncaughtException and send a response', function () {

            $exception = new UncaughtException($this->exception);
            $handler = mock(RequestHandlerInterface::class);
            $response = new TextResponse('exception');

            $this->factory->with($exception)->returns($handler);
            $handler->handle->with($this->request)->returns($response);

            $headers = [];

            allow('header')->toBeCalled()->andRun(function ($header) use (&$headers) {

                $headers[] = $header;

            });

            ob_start();

            ($this->handler)($this->exception);

            $sent = ob_get_clean();

            expect($headers)->toContain('HTTP/1.1 200 OK');
            expect($headers)->toContain('content-type: text/plain; charset=utf-8');
            expect($sent)->toContain('exception');

        });

    });

});
