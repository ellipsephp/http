<?php

use Ellipse\Http\Exceptions\Response\ExceptionResponseFactoryInterface;
use Ellipse\Http\Exceptions\Response\SimpleJsonResponseFactory;

describe('SimpleJsonResponseFactory', function () {

    beforeEach(function () {

        $this->factory = new SimpleJsonResponseFactory;

    });

    it('should implement ExceptionResponseFactoryInterface', function () {

        expect($this->factory)->toBeAnInstanceOf(ExceptionResponseFactoryInterface::class);

    });

    describe('->response()', function () {

        it('should return a simple json response', function () {

            $exception = new Exception;

            $test = $this->factory->response($exception);

            expect($test->getStatusCode())->toEqual(500);
            expect($test->getHeaderLine('Content-type'))->toContain('application/json');
            expect(json_decode((string) $test->getBody(), true))->toEqual([
                'message' => 'Server error',
            ]);

        });

    });

});
