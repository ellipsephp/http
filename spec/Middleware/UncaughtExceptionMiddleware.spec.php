<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Http\Factory\Diactoros\ResponseFactory;

use Ellipse\Exceptions\ExceptionHandlerMiddleware;
use Ellipse\Http\Middleware\UncaughtExceptionMiddleware;

describe('UncaughtExceptionMiddleware', function () {

    beforeEach(function () {

        $this->factory = new ResponseFactory;

    });

    it('should extend ExceptionHandlerMiddleware', function () {

        $test = new UncaughtExceptionMiddleware($this->factory, false);

        expect($test)->toBeAnInstanceOf(ExceptionHandlerMiddleware::class);

    });

    describe('->process()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class);
            $this->handler = mock(RequestHandlerInterface::class);

        });

        context('when debug option is set to false', function () {

            beforeEach(function () {

                $this->middleware = new UncaughtExceptionMiddleware($this->factory, false);

            });

            context('when the given request handler does not throw an exception', function () {

                it('should proxy the given request handler', function () {

                    $response = mock(ResponseInterface::class)->get();

                    $this->handler->handle->with($this->request)->returns($response);

                    $test = $this->middleware->process($this->request->get(), $this->handler->get());

                    expect($test)->toBe($response);

                });

            });

            context('when the given request handler throws an exception', function () {

                beforeEach(function () {

                    $exception = mock(Throwable::class)->get();

                    $this->handler->handle->with($this->request->get())->throws($exception);

                });

                context('when the request is an ajax request', function () {

                    it('should return a simple json response', function () {

                        $this->request->getServerParams->returns(['HTTP_X_REQUESTED_WITH' => 'xmlhttprequest']);

                        $test = $this->middleware->process($this->request->get(), $this->handler->get());

                        expect($test->getStatusCode())->toEqual(500);
                        expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                        expect(json_decode((string) $test->getBody(), true))->toContain('Server error');

                    });

                });

                context('when the request is not an ajax request', function () {

                    beforeEach(function () {

                        $this->request->getServerParams->returns([]);

                    });

                    context('when the request prefers json contents', function () {

                        it('should return a simple json response', function () {

                            $this->request->getHeaderLine->with('Accept', '*/*')->returns('application/json');

                            $test = $this->middleware->process($this->request->get(), $this->handler->get());

                            expect($test->getStatusCode())->toEqual(500);
                            expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                            expect(json_decode((string) $test->getBody(), true))->toContain('Server error');

                        });

                    });

                    context('when the request do not prefer json contents', function () {

                        it('should return a simple html response', function () {

                            $this->request->getHeaderLine->with('Accept', '*/*')->returns('*/*');

                            $test = $this->middleware->process($this->request->get(), $this->handler->get());

                            expect($test->getStatusCode())->toEqual(500);
                            expect($test->getHeaderLine('Content-type'))->toContain('text/html');
                            expect((string) $test->getBody())->toContain('Server error');

                        });

                    });

                });

            });

        });

        context('when debug option is set to true', function () {

            beforeEach(function () {

                $this->middleware = new UncaughtExceptionMiddleware($this->factory, true);

            });

            context('when the given request handler does not throw an exception', function () {

                it('should proxy the given request handler', function () {

                    $response = mock(ResponseInterface::class)->get();

                    $this->handler->handle->with($this->request)->returns($response);

                    $test = $this->middleware->process($this->request->get(), $this->handler->get());

                    expect($test)->toBe($response);

                });

            });

            context('when the given request handler throws an exception', function () {

                beforeEach(function () {

                    $this->exception = mock(Throwable::class)->get();

                    $this->handler->handle->with($this->request->get())->throws($this->exception);

                });

                context('when the request is an ajax request', function () {

                    it('should return a simple json response', function () {

                        $this->request->getServerParams->returns(['HTTP_X_REQUESTED_WITH' => 'xmlhttprequest']);

                        $test = $this->middleware->process($this->request->get(), $this->handler->get());

                        expect($test->getStatusCode())->toEqual(500);
                        expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                        expect(json_decode((string) $test->getBody(), true))->toContain(get_class($this->exception));

                    });

                });

                context('when the request is not an ajax request', function () {

                    beforeEach(function () {

                        $this->request->getServerParams->returns([]);

                    });

                    context('when the request prefers json contents', function () {

                        it('should return a simple json response', function () {

                            $this->request->getHeaderLine->with('Accept', '*/*')->returns('application/json');

                            $test = $this->middleware->process($this->request->get(), $this->handler->get());

                            expect($test->getStatusCode())->toEqual(500);
                            expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                            expect(json_decode((string) $test->getBody(), true))->toContain(get_class($this->exception));

                        });

                    });

                    context('when the request do not prefer json contents', function () {

                        it('should return a simple html response', function () {

                            $this->request->getHeaderLine->with('Accept', '*/*')->returns('*/*');

                            $test = $this->middleware->process($this->request->get(), $this->handler->get());

                            expect($test->getStatusCode())->toEqual(500);
                            expect($test->getHeaderLine('Content-type'))->toContain('text/html');
                            expect((string) $test->getBody())->toContain(get_class($this->exception));

                        });

                    });

                });

            });

        });

    });

});
