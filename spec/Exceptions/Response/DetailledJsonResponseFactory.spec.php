<?php

use Ellipse\Http\Exceptions\Response\ExceptionResponseFactoryInterface;
use Ellipse\Http\Exceptions\Response\DetailledJsonResponseFactory;

describe('DetailledJsonResponseFactory', function () {

    beforeEach(function () {

        $this->factory = new DetailledJsonResponseFactory;

    });

    it('should implement ExceptionResponseFactoryInterface', function () {

        expect($this->factory)->toBeAnInstanceOf(ExceptionResponseFactoryInterface::class);

    });

    describe('->response()', function () {

        it('should return a detailled json response', function () {

            $message = 'the exception message';

            $exception = new Exception($message);

            $test = $this->factory->response($exception);

            expect($test->getStatusCode())->toEqual(500);
            expect($test->getHeaderLine('Content-type'))->toContain('application/json');
            expect(json_decode((string) $test->getBody(), true))->toContain(get_class($exception));
            expect(json_decode((string) $test->getBody(), true))->toContain($message);

        });

    });

});
