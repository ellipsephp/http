<?php

use Ellipse\Http\Exceptions\Response\ExceptionResponseFactoryInterface;
use Ellipse\Http\Exceptions\Response\SimpleHtmlResponseFactory;

describe('SimpleHtmlResponseFactory', function () {

    beforeEach(function () {

        $this->factory = new SimpleHtmlResponseFactory;

    });

    it('should implement ExceptionResponseFactoryInterface', function () {

        expect($this->factory)->toBeAnInstanceOf(ExceptionResponseFactoryInterface::class);

    });

    describe('->response()', function () {

        it('should return a simple html response', function () {

            $exception = new Exception;

            $test = $this->factory->response($exception);

            expect($test->getStatusCode())->toEqual(500);
            expect($test->getHeaderLine('Content-type'))->toContain('text/html');
            expect((string) $test->getBody())->toContain('Server error');

        });

    });

});
