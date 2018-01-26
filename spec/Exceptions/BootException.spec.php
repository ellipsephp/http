<?php

use function Eloquent\Phony\Kahlan\mock;

use Ellipse\Http\Exceptions\BootException;
use Ellipse\Http\Exceptions\HttpExceptionInterface;

describe('BootException', function () {

    beforeEach(function () {

        $this->previous = mock(Throwable::class)->get();

        $this->exception = new BootException($this->previous);

    });

    it('should implements HttpExceptionInterface', function () {

        expect($this->exception)->toBeAnInstanceOf(HttpExceptionInterface::class);

    });

    describe('->getPrevious()', function () {

        it('should return the previous exception', function () {

            $test = $this->exception->getPrevious();

            expect($test)->toBe($this->previous);

        });

    });

});
