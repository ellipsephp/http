<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Http\Handlers\RequestBasedRequestHandler;

describe('RequestBasedRequestHandler', function () {

    it('should implement RequestHandlerInterface', function () {

        $test = new RequestBasedRequestHandler([]);

        expect($test)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handle()', function () {

        beforeEach(function () {

            $this->html = mock(RequestHandlerInterface::class);
            $this->text = mock(RequestHandlerInterface::class);

            $this->request = mock(ServerRequestInterface::class);
            $this->response = mock(ResponseInterface::class)->get();

        });

        context('when a request handler is associated to application/json mediatype', function () {

            beforeEach(function () {

                $this->json = mock(RequestHandlerInterface::class);

                $this->handler = new RequestBasedRequestHandler([
                    'text/html' => $this->html->get(),
                    'application/json' => $this->json->get(),
                    'text/plain' => $this->text->get(),
                ]);

            });

            context('when request is an ajax request', function () {

                it('should proxy the request handler associated to application/json mediatype', function () {

                    $this->request->getServerParams->returns(['HTTP_X_REQUESTED_WITH' => 'xmlhttprequest']);

                    $this->json->handle->with($this->request)->returns($this->response);

                    $test = $this->handler->handle($this->request->get());

                    expect($test)->toBe($this->response);

                });

            });

            context('when the request is not an ajax request', function () {

                beforeEach(function () {

                    $this->request->getServerParams->returns([]);

                });

                context('when a request handler is associated to a mediatype matching the request accept header', function () {

                    it('should proxy the request handler associated to the preferred mediatype', function () {

                        $this->request->getHeaderLine->with('Accept', '*/*')->returns('text/plain');

                        $this->text->handle->with($this->request)->returns($this->response);

                        $test = $this->handler->handle($this->request->get());

                        expect($test)->toBe($this->response);

                    });

                });

                context('when no request handler is associated to a mediatype matching the request accept header', function () {

                    it('should proxy the first request handler', function () {

                        $this->request->getHeaderLine->with('Accept', '*/*')->returns('text/csv');

                        $this->html->handle->with($this->request)->returns($this->response);

                        $test = $this->handler->handle($this->request->get());

                        expect($test)->toBe($this->response);

                    });

                });

            });

        });

        context('when no request handler is associated to application/json mediatype', function () {

            beforeEach(function () {

                $this->handler = new RequestBasedRequestHandler([
                    'text/html' => $this->html->get(),
                    'text/plain' => $this->text->get(),
                ]);

            });

            context('when request is an ajax request', function () {

                context('when a request handler is associated to a mediatype matching the request accept header', function () {

                    it('should proxy the request handler associated to the preferred mediatype', function () {

                        $this->request->getHeaderLine->with('Accept', '*/*')->returns('text/plain');

                        $this->text->handle->with($this->request)->returns($this->response);

                        $test = $this->handler->handle($this->request->get());

                        expect($test)->toBe($this->response);

                    });

                });

                context('when no request handler is associated to a mediatype matching the request accept header', function () {

                    it('should proxy the first request handler', function () {

                        $this->request->getHeaderLine->with('Accept', '*/*')->returns('text/csv');

                        $this->html->handle->with($this->request)->returns($this->response);

                        $test = $this->handler->handle($this->request->get());

                        expect($test)->toBe($this->response);

                    });

                });

            });

            context('when the request is not an ajax request', function () {

                beforeEach(function () {

                    $this->request->getServerParams->returns([]);

                });

                context('when a request handler is associated to a mediatype matching the request accept header', function () {

                    it('should proxy the request handler associated to the preferred mediatype', function () {

                        $this->request->getHeaderLine->with('Accept', '*/*')->returns('text/plain');

                        $this->text->handle->with($this->request)->returns($this->response);

                        $test = $this->handler->handle($this->request->get());

                        expect($test)->toBe($this->response);

                    });

                });

                context('when no request handler is associated to a mediatype matching the request accept header', function () {

                    it('should proxy the first request handler', function () {

                        $this->request->getHeaderLine->with('Accept', '*/*')->returns('text/csv');

                        $this->html->handle->with($this->request)->returns($this->response);

                        $test = $this->handler->handle($this->request->get());

                        expect($test)->toBe($this->response);

                    });

                });

            });

        });

    });

});
