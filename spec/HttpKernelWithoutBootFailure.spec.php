<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\HttpKernel;
use Ellipse\Http\HttpKernelWithoutBootFailure;
use Ellipse\Http\Exceptions\HttpException;

describe('HttpKernelWithoutBootFailure', function () {

    beforeEach(function () {

        $this->handler = mock(RequestHandlerInterface::class);

    });

    it('should extend HttpKernel', function () {

        $test = new HttpKernelWithoutBootFailure($this->handler->get(), false);

        expect($test)->toBeAnInstanceOf(HttpKernel::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class);

        });

        context('when debug option is set to true', function () {

            beforeEach(function () {

                $this->kernel = new HttpKernelWithoutBootFailure($this->handler->get(), true);

            });

            context('when the request handler throws an exception', function () {

                beforeEach(function () {

                    $exception = mock(Throwable::class)->get();

                    $this->handler->handle->with($this->request->get())->throws($exception);

                });

                context('when the request is an ajax request', function () {

                    it('should return a simple json response', function () {

                        $this->request->getServerParams->returns(['HTTP_X_REQUESTED_WITH' => 'xmlhttprequest']);

                        $test = $this->kernel->handle($this->request->get());

                        expect(json_decode((string) $test->getBody(), true)['context'])->toContain(HttpException::class);

                    });

                });

                context('when the request is not an ajax request', function () {

                    beforeEach(function () {

                        $this->request->getServerParams->returns([]);

                    });

                    context('when the request prefers json contents', function () {

                        it('should return a simple json response', function () {

                            $this->request->getHeaderLine->with('Accept', '*/*')->returns('application/json');

                            $test = $this->kernel->handle($this->request->get());

                            expect(json_decode((string) $test->getBody(), true)['context'])->toContain(HttpException::class);

                        });

                    });

                    context('when the request do not prefer json contents', function () {

                        it('should return a simple html response', function () {

                            $this->request->getHeaderLine->with('Accept', '*/*')->returns('*/*');

                            $test = $this->kernel->handle($this->request->get());

                            expect((string) $test->getBody())->toContain(HttpException::class);

                        });

                    });

                });

            });

        });

    });

});
