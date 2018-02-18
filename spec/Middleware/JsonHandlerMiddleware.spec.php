<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Negotiation\Negotiator;

use Ellipse\Http\Middleware\JsonHandlerMiddleware;

describe('JsonHandlerMiddleware', function () {

    beforeEach(function () {

        $this->negotiator = new Negotiator;
        $this->priorities = ['text/html', 'application/json'];
        $this->handler = mock(RequestHandlerInterface::class);

        $this->middleware = new JsonHandlerMiddleware($this->negotiator, $this->priorities, $this->handler->get());

    });

    it('should implement MiddlewareInterface', function () {

        expect($this->middleware)->toBeAnInstanceOf(MiddlewareInterface::class);

    });

    describe('->process()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class);
            $this->response = mock(ResponseInterface::class)->get();
            $this->delegate = mock(RequestHandlerInterface::class);

        });

        context('when request is an ajax request', function () {

            it('should proxy the json handler', function () {

                $this->request->getServerParams->returns(['HTTP_X_REQUESTED_WITH' => 'xmlhttprequest']);

                $this->handler->handle->with($this->request)->returns($this->response);

                $test = $this->middleware->process($this->request->get(), $this->delegate->get());

                expect($test)->toBe($this->response);

            });

        });

        context('when the request is not an ajax request', function () {

            beforeEach(function () {

                $this->request->getServerParams->returns([]);

            });

            context('when request prefers json mediatype', function () {

                it('should proxy the json handler', function () {

                    $this->request->getHeaderLine->with('Accept', '*/*')->returns('application/json');

                    $this->handler->handle->with($this->request)->returns($this->response);

                    $test = $this->middleware->process($this->request->get(), $this->delegate->get());

                    expect($test)->toBe($this->response);

                });

            });

            context('when request does not prefer json mediatype', function () {

                it('should proxy the given handler', function () {

                    $this->request->getHeaderLine->with('Accept', '*/*')->returns('text/html');

                    $this->delegate->handle->with($this->request)->returns($this->response);

                    $test = $this->middleware->process($this->request->get(), $this->delegate->get());

                    expect($test)->toBe($this->response);

                });

            });

        });

    });

});
