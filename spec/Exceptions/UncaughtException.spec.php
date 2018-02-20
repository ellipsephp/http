<?php

use function Eloquent\Phony\Kahlan\mock;

use Ellipse\Http\Exceptions\UncaughtException;
use Ellipse\Http\Exceptions\HttpExceptionInterface;

describe('UncaughtException', function () {

    beforeEach(function () {

        $this->previous = mock(Throwable::class)->get();

        $this->exception = new UncaughtException($this->previous);

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
