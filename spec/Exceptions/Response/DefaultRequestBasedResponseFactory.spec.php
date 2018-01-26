<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Http\Exceptions\Response\DefaultRequestBasedResponseFactory;

describe('DefaultRequestBasedResponseFactory', function () {

    beforeEach(function () {

        $this->request = mock(ServerRequestInterface::class);

    });

    describe('->response()', function () {

        context('when the debug value is set to true', function () {

            beforeEach(function () {

                $this->factory = new DefaultRequestBasedResponseFactory(true);

            });

            context('when the request accept html contents', function () {

                it('should return a detailled html response', function () {

                    $this->request->getHeaderLine->with('Accept', '*')->returns('text/html');

                    $message = 'the exception message';

                    $exception = new Exception($message);

                    $test = $this->factory->response($this->request->get(), $exception);

                    expect($test->getStatusCode())->toEqual(500);
                    expect($test->getHeaderLine('Content-type'))->toContain('text/html');
                    expect((string) $test->getBody())->toContain($message);

                });

            });

            context('when the request accept json contents', function () {

                it('should return a detailled json response', function () {

                    $this->request->getHeaderLine->with('Accept', '*')->returns('application/json');

                    $message = 'the exception message';

                    $exception = new Exception($message);

                    $test = $this->factory->response($this->request->get(), $exception);

                    expect($test->getStatusCode())->toEqual(500);
                    expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                    expect(json_decode((string) $test->getBody(), true))->toContain(get_class($exception));
                    expect(json_decode((string) $test->getBody(), true))->toContain($message);

                });

            });

        });

        context('when the debug value is set to false', function () {

            beforeEach(function () {

                $this->factory = new DefaultRequestBasedResponseFactory(false);

            });

            context('when the request accept html contents', function () {

                it('should return a simple html response', function () {

                    $this->request->getHeaderLine->with('Accept', '*')->returns('text/html');

                    $exception = new Exception;

                    $test = $this->factory->response($this->request->get(), $exception);

                    expect($test->getStatusCode())->toEqual(500);
                    expect($test->getHeaderLine('Content-type'))->toContain('text/html');
                    expect((string) $test->getBody())->toContain('Server error');

                });

            });

            context('when the request accept json contents', function () {

                it('should return a simple json response', function () {

                    $this->request->getHeaderLine->with('Accept', '*')->returns('application/json');

                    $exception = new Exception;

                    $test = $this->factory->response($this->request->get(), $exception);

                    expect($test->getStatusCode())->toEqual(500);
                    expect($test->getHeaderLine('Content-type'))->toContain('application/json');
                    expect(json_decode((string) $test->getBody(), true))->toEqual([
                        'message' => 'Server error',
                    ]);

                });

            });

        });

    });

});
