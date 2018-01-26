<?php

use Ellipse\Http\Exceptions\Response\ExceptionResponseFactoryInterface;
use Ellipse\Http\Exceptions\Response\DetailledHtmlResponseFactory;

describe('DetailledHtmlResponseFactory', function () {

    beforeEach(function () {

        $this->factory = new DetailledHtmlResponseFactory;

    });

    it('should implement ExceptionResponseFactoryInterface', function () {

        expect($this->factory)->toBeAnInstanceOf(ExceptionResponseFactoryInterface::class);

    });

    describe('->response()', function () {

        it('should return a detailled html response', function () {

            $message = 'the exception message';

            $exception = new Exception($message);

            $test = $this->factory->response($exception);

            expect($test->getStatusCode())->toEqual(500);
            expect($test->getHeaderLine('Content-type'))->toContain('text/html');
            expect((string) $test->getBody())->toContain($message);

        });

    });

});
