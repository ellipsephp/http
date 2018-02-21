<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Server\RequestHandlerInterface;

use Interop\Http\Factory\ResponseFactoryInterface;

use Ellipse\Http\HttpKernelFactory;
use Ellipse\Http\HttpKernelWithoutBootFailure;
use Ellipse\Http\HttpKernelWithBootFailure;
use Ellipse\Http\Exceptions\HttpKernelTypeException;

describe('HttpKernelFactory', function () {

    beforeEach(function () {

        $this->bootstrap = stub();
        $this->factory = mock(ResponseFactoryInterface::class)->get();

        $this->kernel = new HttpKernelFactory($this->bootstrap);

    });

    describe('->__invoke()', function () {

        context('when the bootstrap does not throw an exception', function () {

            context('when the bootstrap returns an implementation of RequestHandlerInterface', function () {

                context('when the debug value is set to true', function () {

                    it('should return a http kernel wrapped adound the request handler with debug value set to true', function () {

                        $handler = mock(RequestHandlerInterface::class)->get();

                        $this->bootstrap->with($this->factory, 'env', true)->returns($handler);

                        $test = ($this->kernel)($this->factory, 'env', true);

                        $kernel = new HttpKernelWithoutBootFailure($handler, $this->factory, true);

                        expect($test)->toEqual($kernel);

                    });

                });

                context('when the debug value is set to false', function () {

                    it('should return a http kernel wrapped adound the request handler with debug value set to false', function () {

                        $handler = mock(RequestHandlerInterface::class)->get();

                        $this->bootstrap->with($this->factory, 'env', false)->returns($handler);

                        $test = ($this->kernel)($this->factory, 'env', false);

                        $kernel = new HttpKernelWithoutBootFailure($handler, $this->factory, false);

                        expect($test)->toEqual($kernel);

                    });

                });

            });

            context('when the bootstrap does not return an implementation of RequestHandlerInterface', function () {

                beforeEach(function () {

                    $this->exception = new HttpKernelTypeException('handler');

                    allow(HttpKernelTypeException::class)->toBe($this->exception);

                });

                context('when the debug value is set to true', function () {

                    it('should return a http kernel with boot failure with a HttpKernelTypeException and the debug value set to true', function () {

                        $this->bootstrap->with($this->factory, 'env', true)->returns('handler');

                        $test = ($this->kernel)($this->factory, 'env', true);

                        $kernel = new HttpKernelWithBootFailure($this->exception, $this->factory, true);

                        expect($test)->toEqual($kernel);

                    });

                });

                context('when the debug value is set to false', function () {

                    it('should return a http kernel with boot failure with a HttpKernelTypeException and the debug value set to false', function () {

                        $this->bootstrap->with($this->factory, 'env', false)->returns('handler');

                        $test = ($this->kernel)($this->factory, 'env', false);

                        $kernel = new HttpKernelWithBootFailure($this->exception, $this->factory, false);

                        expect($test)->toEqual($kernel);

                    });

                });

            });

        });

        context('when the bootstrap throws an exception', function () {

            context('when the debug value is set to true', function () {

                it('should return a http kernel with boot failure with the exception and the debug value set to true', function () {

                    $exception = mock(Throwable::class)->get();

                    $this->bootstrap->with($this->factory, 'env', true)->throws($exception);

                    $test = ($this->kernel)($this->factory, 'env', true);

                    $kernel = new HttpKernelWithBootFailure($exception, $this->factory, true);

                    expect($test)->toBeAnInstanceOf(HttpKernelWithBootFailure::class);

                });

            });

            context('when the debug value is set to false', function () {

                it('should return a http kernel with boot failure with the exception and the debug value set to false', function () {

                    $exception = mock(Throwable::class)->get();

                    $this->bootstrap->with($this->factory, 'env', false)->throws($exception);

                    $test = ($this->kernel)($this->factory, 'env', false);

                    $kernel = new HttpKernelWithBootFailure($exception, $this->factory, false);

                    expect($test)->toEqual($kernel);

                });

            });

        });

    });

});
