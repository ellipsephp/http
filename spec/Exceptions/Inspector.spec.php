<?php

use Ellipse\Http\Exceptions\Inspector;

describe('Inspector', function () {

    beforeEach(function () {

        $this->exception3 = new Exception;
        $this->exception2 = new Exception('', 0, $this->exception3);
        $this->exception1 = new Exception('', 0, $this->exception2);

        $this->inspector = new Inspector($this->exception1);

    });

    describe('->current()', function () {

        it('should return the exception', function () {

            $test = $this->inspector->current();

            expect($test)->toBe($this->exception1);

        });

    });

    describe('->inner()', function () {

        it('should return the last previous exception', function () {

            $test = $this->inspector->inner();

            expect($test)->toBe($this->exception3);

        });

    });

    describe('->linearized()', function () {

        it('should return an array containing the exception and all its previous exceptions', function () {

            $test = $this->inspector->linearized();

            expect($test)->toEqual([
                $this->exception3,
                $this->exception2,
                $this->exception1,
            ]);

        });

    });

});
