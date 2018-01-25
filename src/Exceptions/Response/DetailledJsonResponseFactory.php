<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

use Throwable;

use Psr\Http\Message\ResponseInterface;

use Zend\Diactoros\Response\JsonResponse;

use Ellipse\Http\Exceptions\ExceptionDetails;

class DetailledJsonResponseFactory
{
    public function response(Throwable $e): ResponseInterface
    {
        $details = new ExceptionDetails($e);

        return new JsonResponse([
            'type' => get_class($details->inner()),
            'message' => $details->inner()->getMessage(),
            'context' => [
                'type' => get_class($details->current()),
                'message' => $details->current()->getMessage(),
            ],
            'trace' => $details->inner()->getTrace(),
        ], 500);
    }
}
