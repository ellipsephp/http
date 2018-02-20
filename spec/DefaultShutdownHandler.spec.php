<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Ellipse\Http\DefaultShutdownHandler;

describe('DefaultShutdownHandler', function () {

    beforeEach(function () {

        $this->request = mock(ServerRequestInterface::class)->get();
        $this->prototype = mock(ResponseInterface::class)->get();

        $this->handler = new DefaultShutdownHandler($this->request, $this->prototype, true);

    });

    describe('->__invoke()', function () {

        context('when there is no last error', function () {

            it('should run without error', function () {

                expect($this->handler)->not->toThrow();

            });

        });

    });

});
