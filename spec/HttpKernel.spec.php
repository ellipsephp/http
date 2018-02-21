<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Http\Factory\Diactoros\ResponseFactory;

use Ellipse\Http\HttpKernel;

describe('HttpKernel', function () {

    beforeEach(function () {

        $this->handler = mock(RequestHandlerInterface::class);
        $this->factory = new ResponseFactory;

    });

    it('should implement RequestHandlerInterface', function () {

        $test = new HttpKernel($this->handler->get(), $this->factory, false);

        expect($test)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class);

        });

        context('when debug option is set to false', function () {

            beforeEach(function () {

                $this->kernel = new HttpKernel($this->handler->get(), $this->factory, false);

            });

            context('when the request handler does not throw an exception', function () {

                it('should proxy the request handler', function () {

                    $response = mock(ResponseInterface::class)->get();

                    $this->handler->handle->with($this->request)->returns($response);

                    $test = $this->kernel->handle($this->request->get());

                    expect($test)->toBe($response);

                });

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

                            $test = $this->kernel->handle($this->request->get());

                            expect($test->getStatusCode())->toEqual(500);
                            expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                            expect(json_decode((string) $test->getBody(), true))->toContain('Server error');

                        });

                    });

                    context('when the request do not prefer json contents', function () {

                        it('should return a simple html response', function () {

                            $this->request->getHeaderLine->with('Accept', '*/*')->returns('*/*');

                            $test = $this->kernel->handle($this->request->get());

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

                $this->kernel = new HttpKernel($this->handler->get(), $this->factory, true);

            });

            context('when the request handler does not throw an exception', function () {

                it('should proxy the request handler', function () {

                    $response = mock(ResponseInterface::class)->get();

                    $this->handler->handle->with($this->request)->returns($response);

                    $test = $this->kernel->handle($this->request->get());

                    expect($test)->toBe($response);

                });

            });

            context('when the request handler throws an exception', function () {

                beforeEach(function () {

                    $this->exception = mock(Throwable::class)->get();

                    $this->handler->handle->with($this->request->get())->throws($this->exception);

                });

                context('when the request is an ajax request', function () {

                    it('should return a simple json response', function () {

                        $this->request->getServerParams->returns(['HTTP_X_REQUESTED_WITH' => 'xmlhttprequest']);

                        $test = $this->kernel->handle($this->request->get());

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

                            $test = $this->kernel->handle($this->request->get());

                            expect($test->getStatusCode())->toEqual(500);
                            expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                            expect(json_decode((string) $test->getBody(), true))->toContain(get_class($this->exception));

                        });

                    });

                    context('when the request do not prefer json contents', function () {

                        it('should return a simple html response', function () {

                            $this->request->getHeaderLine->with('Accept', '*/*')->returns('*/*');

                            $test = $this->kernel->handle($this->request->get());

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
