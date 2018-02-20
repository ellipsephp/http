<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Zend\Diactoros\Response;

use Ellipse\Http\HttpKernel;
use Ellipse\Http\HttpKernelWithBootFailure;
use Ellipse\Http\Exceptions\BootException;

describe('HttpKernelWithBootFailure', function () {

    beforeEach(function () {

        $this->exception = mock(Throwable::class)->get();
        $this->prototype = new Response;

    });

    it('should extend HttpKernel', function () {

        $test = new HttpKernelWithBootFailure($this->exception, $this->prototype, false);

        expect($test)->toBeAnInstanceOf(HttpKernel::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class);

        });

        context('when debug option is set to true', function () {

            beforeEach(function () {

                $this->kernel = new HttpKernelWithBootFailure($this->exception, $this->prototype, true);

            });

            context('when the request is an ajax request', function () {

                it('should return a simple json response', function () {

                    $this->request->getServerParams->returns(['HTTP_X_REQUESTED_WITH' => 'xmlhttprequest']);

                    $test = $this->kernel->handle($this->request->get());

                    expect(json_decode((string) $test->getBody(), true)['context'])->toContain(BootException::class);

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

                        expect(json_decode((string) $test->getBody(), true)['context'])->toContain(BootException::class);

                    });

                });

                context('when the request do not prefer json contents', function () {

                    it('should return a simple html response', function () {

                        $this->request->getHeaderLine->with('Accept', '*/*')->returns('*/*');

                        $test = $this->kernel->handle($this->request->get());

                        expect((string) $test->getBody())->toContain(BootException::class);

                    });

                });

            });

        });

    });

});
