<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Http\ShutdownHandler;
use Ellipse\Http\Exceptions\Response\RequestBasedResponseFactory;

describe('ShutdownHandler', function () {

    beforeEach(function () {

        $this->request = mock(ServerRequestInterface::class)->get();
        $this->factory = mock(RequestBasedResponseFactory::class);

        $this->handler = new ShutdownHandler($this->request, $this->factory->get());

    });

    describe('->__invoke()', function () {

        context('when there is no last error', function () {

            it('should run without error', function () {

                expect($this->handler)->not->toThrow();

            });

        });

    });

});
