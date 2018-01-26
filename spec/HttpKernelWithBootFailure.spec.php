<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Dispatcher;
use Ellipse\Http\AbstractHttpKernel;
use Ellipse\Http\HttpKernelWithBootFailure;

describe('HttpKernelWithBootFailure', function () {

    beforeEach(function () {

        $this->exception = mock(Throwable::class)->get();

    });

    it('should extend Dispatcher', function () {

        $test = new HttpKernelWithBootFailure($this->exception, true);

        expect($test)->toBeAnInstanceOf(Dispatcher::class);

    });

    it('should extend AbstractHttpKernel', function () {

        $test = new HttpKernelWithBootFailure($this->exception, true);

        expect($test)->toBeAnInstanceOf(AbstractHttpKernel::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class);

        });

        context('when debug option is set to false', function () {

            beforeEach(function () {

                $this->kernel = new HttpKernelWithBootFailure($this->exception, false);

            });

            context('when the request accept html contents', function () {

                it('should return a simple html response', function () {

                    $this->request->getHeaderLine->with('Accept', '*')->returns('text/html');

                    $test = $this->kernel->handle($this->request->get());

                    expect($test->getStatusCode())->toEqual(500);
                    expect($test->getHeaderLine('Content-type'))->toContain('text/html');
                    expect((string) $test->getBody())->toContain('Server error');

                });

            });

            context('when the request accept json contents', function () {

                it('should return a simple json response', function () {

                    $this->request->getHeaderLine->with('Accept', '*')->returns('application/json');

                    $test = $this->kernel->handle($this->request->get());

                    expect($test->getStatusCode())->toEqual(500);
                    expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                    expect(json_decode((string) $test->getBody(), true))->toContain('Server error');

                });

            });

        });

        context('when debug option is set to true', function () {

            beforeEach(function () {

                $this->kernel = new HttpKernelWithBootFailure($this->exception, true);

            });

            context('when the request accept html contents', function () {

                it('should return a detailled html response', function () {

                    $this->request->getHeaderLine->with('Accept', '*')->returns('text/html');

                    $test = $this->kernel->handle($this->request->get());

                    expect($test->getStatusCode())->toEqual(500);
                    expect($test->getHeaderLine('Content-type'))->toContain('text/html');
                    expect((string) $test->getBody())->toContain('Uncaught exception while booting the http kernel');
                    expect((string) $test->getBody())->toContain(get_class($this->exception));

                });

            });

            context('when the request accept json contents', function () {

                it('should return a detailled json response', function () {

                    $this->request->getHeaderLine->with('Accept', '*')->returns('application/json');

                    $test = $this->kernel->handle($this->request->get());

                    expect($test->getStatusCode())->toEqual(500);
                    expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                    expect(json_decode((string) $test->getBody(), true))->toContain(get_class($this->exception));
                    expect(json_decode((string) $test->getBody(), true)['context'])->toContain('Uncaught exception while booting the http kernel');

                });

            });

        });

    });

});
