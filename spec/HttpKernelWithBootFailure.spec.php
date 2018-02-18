<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Http\HttpKernel;
use Ellipse\Http\HttpKernelWithBootFailure;

describe('HttpKernelWithBootFailure', function () {

    beforeEach(function () {

        $this->exception = mock(Throwable::class)->get();

        $this->kernel = new HttpKernelWithBootFailure($this->exception, true);

    });

    it('should extend HttpKernel', function () {

        expect($this->kernel)->toBeAnInstanceOf(HttpKernel::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class);

            $this->request->getServerParams->returns([]);

        });

        context('when debug option is set to true', function () {

            context('when the request accepts json contents', function () {

                it('should return a detailled json response', function () {

                    $this->request->getHeaderLine->with('Accept', '*/*')->returns('application/json');

                    $test = $this->kernel->handle($this->request->get());

                    expect($test->getStatusCode())->toEqual(500);
                    expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                    expect(json_decode((string) $test->getBody(), true))->toContain(get_class($this->exception));
                    expect(json_decode((string) $test->getBody(), true)['context'])->toContain('Uncaught exception while booting the http kernel');

                });

            });

            context('when the request does not accept json contents', function () {

                it('should return a detailled html response', function () {

                    $this->request->getHeaderLine->with('Accept', '*/*')->returns('*/*');

                    $test = $this->kernel->handle($this->request->get());

                    expect($test->getStatusCode())->toEqual(500);
                    expect($test->getHeaderLine('Content-type'))->toContain('text/html');
                    expect((string) $test->getBody())->toContain('Uncaught exception while booting the http kernel');
                    expect((string) $test->getBody())->toContain(get_class($this->exception));

                });

            });

        });

    });

});
