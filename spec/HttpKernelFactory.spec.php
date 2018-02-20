<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\HttpKernelFactory;
use Ellipse\Http\HttpKernelWithoutBootFailure;
use Ellipse\Http\HttpKernelWithBootFailure;
use Ellipse\Http\Exceptions\HttpKernelTypeException;

describe('HttpKernelFactory', function () {

    beforeEach(function () {

        $this->bootstrap = stub();
        $this->prototype = mock(ResponseInterface::class)->get();

        $this->factory = new HttpKernelFactory($this->bootstrap);

    });

    describe('->__invoke()', function () {

        context('when the bootstrap does not throw an exception', function () {

            context('when the bootstrap returns an implementation of RequestHandlerInterface', function () {

                context('when the debug value is set to true', function () {

                    it('should return a http kernel wrapped adound the request handler with debug value set to true', function () {

                        $handler = mock(RequestHandlerInterface::class)->get();

                        $this->bootstrap->with($this->prototype, 'env', true)->returns($handler);

                        $test = ($this->factory)($this->prototype, 'env', true);

                        $kernel = new HttpKernelWithoutBootFailure($handler, $this->prototype, true);

                        expect($test)->toEqual($kernel);

                    });

                });

                context('when the debug value is set to false', function () {

                    it('should return a http kernel wrapped adound the request handler with debug value set to false', function () {

                        $handler = mock(RequestHandlerInterface::class)->get();

                        $this->bootstrap->with($this->prototype, 'env', false)->returns($handler);

                        $test = ($this->factory)($this->prototype, 'env', false);

                        $kernel = new HttpKernelWithoutBootFailure($handler, $this->prototype, false);

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

                        $this->bootstrap->with($this->prototype, 'env', true)->returns('handler');

                        $test = ($this->factory)($this->prototype, 'env', true);

                        $kernel = new HttpKernelWithBootFailure($this->exception, $this->prototype, true);

                        expect($test)->toEqual($kernel);

                    });

                });

                context('when the debug value is set to false', function () {

                    it('should return a http kernel with boot failure with a HttpKernelTypeException and the debug value set to false', function () {

                        $this->bootstrap->with($this->prototype, 'env', false)->returns('handler');

                        $test = ($this->factory)($this->prototype, 'env', false);

                        $kernel = new HttpKernelWithBootFailure($this->exception, $this->prototype, false);

                        expect($test)->toEqual($kernel);

                    });

                });

            });

        });

        context('when the bootstrap throws an exception', function () {

            context('when the debug value is set to true', function () {

                it('should return a http kernel with boot failure with the exception and the debug value set to true', function () {

                    $exception = mock(Throwable::class)->get();

                    $this->bootstrap->with($this->prototype, 'env', true)->throws($exception);

                    $test = ($this->factory)($this->prototype, 'env', true);

                    $kernel = new HttpKernelWithBootFailure($exception, $this->prototype, true);

                    expect($test)->toBeAnInstanceOf(HttpKernelWithBootFailure::class);

                });

            });

            context('when the debug value is set to false', function () {

                it('should return a http kernel with boot failure with the exception and the debug value set to false', function () {

                    $exception = mock(Throwable::class)->get();

                    $this->bootstrap->with($this->prototype, 'env', false)->throws($exception);

                    $test = ($this->factory)($this->prototype, 'env', false);

                    $kernel = new HttpKernelWithBootFailure($exception, $this->prototype, false);

                    expect($test)->toEqual($kernel);

                });

            });

        });

    });

});
