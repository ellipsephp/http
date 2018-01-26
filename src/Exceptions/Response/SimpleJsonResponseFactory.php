<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

use Throwable;

use Psr\Http\Message\ResponseInterface;

use Zend\Diactoros\Response\JsonResponse;

class SimpleJsonResponseFactory
{
    /**
     * Return a simple json response for the given exception.
     *
     * @param \Throwable $e
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response(Throwable $e): ResponseInterface
    {
        return new JsonResponse(['message' => 'Server error'], 500);
    }
}
