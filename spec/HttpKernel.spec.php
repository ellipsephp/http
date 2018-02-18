<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Dispatcher;
use Ellipse\Http\HttpKernel;

describe('HttpKernel', function () {

    beforeEach(function () {

        $this->handler = mock(RequestHandlerInterface::class);

    });

    it('should extend Dispatcher', function () {

        $test = new HttpKernel($this->handler->get(), true);

        expect($test)->toBeAnInstanceOf(Dispatcher::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class);
            $this->response = mock(ResponseInterface::class)->get();

        });

        context('when debug option is set to false', function () {

            beforeEach(function () {

                $this->kernel = new HttpKernel($this->handler->get(), false);

            });

            context('when the handler ->handle() method does not throw an exception', function () {

                it('should proxy the handler ->handle() method', function () {

                    $this->handler->handle->with($this->request)->returns($this->response);

                    $test = $this->kernel->handle($this->request->get());

                    expect($test)->toBe($this->response);

                });

            });

            context('when the handler ->handle() method throws an exception', function () {

                beforeEach(function () {

                    $this->exception = new Exception('Exception message');

                    $this->request->getServerParams->returns([]);
                    $this->handler->handle->with($this->request)->throws($this->exception);

                });

                context('when the request accepts json contents', function () {

                    it('should return a simple json response', function () {

                        $this->request->getHeaderLine->with('Accept', '*/*')->returns('application/json');

                        $test = $this->kernel->handle($this->request->get());

                        expect($test->getStatusCode())->toEqual(500);
                        expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                        expect(json_decode((string) $test->getBody(), true))->toContain('Server error');

                    });

                });

                context('when the request does not accept json contents', function () {

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

        context('when debug option is set to true', function () {

            beforeEach(function () {

                $this->kernel = new HttpKernel($this->handler->get(), true);

            });

            context('when the handler ->handle() method does not throw an exception', function () {

                it('should proxy the handler ->handle() method', function () {

                    $this->handler->handle->with($this->request)->returns($this->response);

                    $test = $this->kernel->handle($this->request->get());

                    expect($test)->toBe($this->response);

                });

            });

            context('when the handler ->handle() method throws an exception', function () {

                beforeEach(function () {

                    $this->exception = new Exception('Exception message');

                    $this->request->getServerParams->returns([]);
                    $this->handler->handle->with($this->request)->throws($this->exception);

                });

                context('when the request accepts json contents', function () {

                    it('should return a detailled json response', function () {

                        $this->request->getHeaderLine->with('Accept', '*/*')->returns('application/json');

                        $test = $this->kernel->handle($this->request->get());

                        expect($test->getStatusCode())->toEqual(500);
                        expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                        expect(json_decode((string) $test->getBody(), true))->toContain(get_class($this->exception));
                        expect(json_decode((string) $test->getBody(), true)['context'])->toContain('Exception message');

                    });

                });

                context('when the request does not accept json contents', function () {

                    it('should return a detailled html response', function () {

                        $this->request->getHeaderLine->with('Accept', '*/*')->returns('*/*');

                        $test = $this->kernel->handle($this->request->get());

                        expect($test->getStatusCode())->toEqual(500);
                        expect($test->getHeaderLine('Content-type'))->toContain('text/html');
                        expect((string) $test->getBody())->toContain(get_class($this->exception));
                        expect((string) $test->getBody())->toContain('Exception message');

                    });

                });

            });

        });

    });

});
