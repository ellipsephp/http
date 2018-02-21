<?php

use function Eloquent\Phony\Kahlan\mock;
use function Eloquent\Phony\Kahlan\stub;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Zend\Diactoros\Response\TextResponse;

use Ellipse\Http\Error\ShutdownHandler;
use Ellipse\Http\Exceptions\FatalException;

describe('ShutdownHandler', function () {

    beforeEach(function () {

        allow('error_reporting')->toBeCalled()->andReturn(E_ALL);

        $this->request = mock(ServerRequestInterface::class)->get();
        $this->factory = stub();

        $this->handler = new ShutdownHandler($this->request, $this->factory);

    });

    describe('->__invoke()', function () {

        context('when there is no last error', function () {

            it('should not fail', function () {

                allow('error_get_last')->toBeCalled()->andReturn(null);

                expect($this->handler)->not->toThrow();

            });

        });

        context('when there is a last error', function () {

            context('when the last error is not fatal', function () {

                it('should not send a response', function () {

                    allow('error_get_last')->toBeCalled()->andReturn(['type' => E_NOTICE]);

                    ob_start();

                    echo 'test';

                    ($this->handler)();

                    $sent = ob_get_clean();

                    expect($sent)->toEqual('test');

                });

            });

            context('when the last error is fatal', function () {

                it('should create an ErrorException from the error, wrap it inside a FatalException and send a response', function () {

                    $error = ['type' => E_ERROR, 'message' => 'message', 'file' => 'file', 'line' => 1];
                    $exception = new FatalException(new ErrorException('message', 0, E_ERROR, 'file', 1));
                    $handler = mock(RequestHandlerInterface::class);
                    $response = new TextResponse('exception');
                    $this->headers = [];

                    $this->factory->with($exception)->returns($handler);
                    $handler->handle->with($this->request)->returns($response);

                    allow('error_get_last')->toBeCalled()->andReturn($error);

                    allow('header')->toBeCalled()->andRun(function ($header) {

                        $this->headers[] = $header;

                    });

                    ob_start();

                    ($this->handler)();

                    $sent = ob_get_clean();

                    expect($this->headers)->toContain('HTTP/1.1 200 OK');
                    expect($this->headers)->toContain('content-type: text/plain; charset=utf-8');
                    expect($sent)->toContain('exception');

                });

            });

        });

    });

});
