<?php

use function Eloquent\Phony\Kahlan\mock;
use function Eloquent\Phony\Kahlan\stub;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Http\ShutdownHandler;

describe('ShutdownHandler', function () {

    beforeEach(function () {

        $this->request = mock(ServerRequestInterface::class)->get();
        $this->factory = stub();

        $this->handler = new ShutdownHandler($this->request, $this->factory);

    });

    describe('->__invoke()', function () {

        context('when there is no last error', function () {

            it('should run without error', function () {

                expect($this->handler)->not->toThrow();

            });

        });

    });

});
