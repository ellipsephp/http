<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Ellipse\Http\Exceptions\Response\ExceptionResponseFactoryInterface;
use Ellipse\Http\Exceptions\Response\RequestBasedResponseFactory;

describe('RequestBasedResponseFactory', function () {

    beforeEach(function () {

        $this->factory1 = mock(ExceptionResponseFactoryInterface::class);
        $this->factory2 = mock(ExceptionResponseFactoryInterface::class);

        $this->factory = new RequestBasedResponseFactory([
            'text/html' => $this->factory1->get(),
            'application/json' => $this->factory2->get(),
        ]);

    });

    describe('->response()', function () {

        beforeEach(function () {

            $this->request = mock(ServerRequestInterface::class);
            $this->response = mock(ResponseInterface::class)->get();

            $this->exception = mock(Throwable::class)->get();

        });

        context('when the request accept header match a factory', function () {

            it('should proxy the ->response() method of the matched factory', function () {

                $this->request->getHeaderLine->with('Accept', '*')->returns('application/json');

                $this->factory2->response->with($this->exception)->returns($this->response);

                $test = $this->factory->response($this->request->get(), $this->exception);

                expect($test)->toBe($this->response);

            });

        });

        context('when the request accept header does not match any factory', function () {

            it('should proxy the ->response() method of the first factory', function () {

                $this->request->getHeaderLine->with('Accept', '*')->returns('accept');

                $this->factory1->response->with($this->exception)->returns($this->response);

                $test = $this->factory->response($this->request->get(), $this->exception);

                expect($test)->toBe($this->response);

            });

        });

    });

});
