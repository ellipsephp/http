<?php

use Ellipse\Http\Exceptions\HttpKernelTypeException;
use Ellipse\Http\Exceptions\HttpExceptionInterface;

describe('HttpKernelTypeException', function () {

    it('should implements HttpExceptionInterface', function () {

        $test = new HttpKernelTypeException('handler');

        expect($test)->toBeAnInstanceOf(HttpExceptionInterface::class);

    });

});
