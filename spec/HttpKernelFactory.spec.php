<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\HttpKernel;
use Ellipse\Http\HttpKernelWithBootFailure;
use Ellipse\Http\HttpKernelFactory;
use Ellipse\Http\Exceptions\HttpKernelTypeException;

describe('HttpKernelFactory', function () {

    beforeEach(function () {

        $this->delegate = stub();

        $this->factory = new HttpKernelFactory($this->delegate);

    });

    describe('->__invoke()', function () {

        context('when the delegate does not throw an exception', function () {

            context('when the delegate returns an implementation of RequestHandlerInterface', function () {

                context('when the debug value is set to true', function () {

                    it('should return a http kernel wrapped adound the request handler with debug value set to true', function () {

                        $handler = mock(RequestHandlerInterface::class)->get();

                        $this->delegate->with('env', true, '/root')->returns($handler);

                        $test = ($this->factory)('env', true, '/root');

                        $kernel = new HttpKernel($handler, true);

                        expect($test)->toEqual($kernel);

                    });

                });

                context('when the debug value is set to false', function () {

                    it('should return a http kernel wrapped adound the request handler with debug value set to false', function () {

                        $handler = mock(RequestHandlerInterface::class)->get();

                        $this->delegate->with('env', false, '/root')->returns($handler);

                        $test = ($this->factory)('env', false, '/root');

                        $kernel = new HttpKernel($handler, false);

                        expect($test)->toEqual($kernel);

                    });

                });

            });

            context('when the delegate does not return an implementation of RequestHandlerInterface', function () {

                beforeEach(function () {

                    $this->request = mock(ServerRequestInterface::class);

                    $this->request->getHeaderLine->with('Accept', '*')->returns('text/html');

                });

                context('when the debug value is set to true', function () {

                    it('should return a http kernel with boot failure with the debug value set to true', function () {

                        $message = (new HttpKernelTypeException('handler'))->getMessage();

                        $this->delegate->with('env', true, '/root')->returns('handler');

                        $test = ($this->factory)('env', true, '/root');

                        expect($test)->toBeAnInstanceOf(HttpKernelWithBootFailure::class);
                        expect((string) $test->handle($this->request->get())->getBody())->toContain($message);

                    });

                });

                context('when the debug value is set to false', function () {

                    it('should return a http kernel with boot failure with the debug value set to false', function () {

                        $this->delegate->with('env', false, '/root')->returns('handler');

                        $test = ($this->factory)('env', false, '/root');

                        expect($test)->toBeAnInstanceOf(HttpKernelWithBootFailure::class);
                        expect((string) $test->handle($this->request->get())->getBody())->toContain('Server error');

                    });

                });

            });

        });

        context('when the delegate throws an exception', function () {

            beforeEach(function () {

                $this->request = mock(ServerRequestInterface::class);

                $this->request->getHeaderLine->with('Accept', '*')->returns('text/html');

            });

            context('when the debug value is set to true', function () {

                it('should return a http kernel with boot failure with the debug value set to true', function () {

                    $message = 'the exception message';

                    $exception = new Exception($message);

                    $this->delegate->with('env', true, '/root')->throws($exception);

                    $test = ($this->factory)('env', true, '/root');

                    expect($test)->toBeAnInstanceOf(HttpKernelWithBootFailure::class);
                    expect((string) $test->handle($this->request->get())->getBody())->toContain($message);

                });

            });

            context('when the debug value is set to false', function () {

                it('should return a http kernel with boot failure with the debug value set to false', function () {

                    $exception = new Exception('the exception message');

                    $this->delegate->with('env', false, '/root')->throws($exception);

                    $test = ($this->factory)('env', false, '/root');

                    expect($test)->toBeAnInstanceOf(HttpKernelWithBootFailure::class);
                    expect((string) $test->handle($this->request->get())->getBody())->toContain('Server error');

                });

            });

        });

    });

});
