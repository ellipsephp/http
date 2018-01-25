<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

use Throwable;

use Psr\Http\Message\ResponseInterface;

use Zend\Diactoros\Response\JsonResponse;

class SimpleJsonResponseFactory
{
    public function response(Throwable $e): ResponseInterface
    {
        return new JsonResponse(['message' => 'Server error'], 500);
    }
}
