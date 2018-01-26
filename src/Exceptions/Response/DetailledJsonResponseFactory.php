<?php declare(strict_types=1);

namespace Ellipse\Http\Exceptions\Response;

use Throwable;

use Psr\Http\Message\ResponseInterface;

use Zend\Diactoros\Response\JsonResponse;

use Ellipse\Http\Exceptions\Inspector;

class DetailledJsonResponseFactory implements ExceptionResponseFactoryInterface
{
    /**
     * Return a detailled json response for the given exception.
     *
     * @param \Throwable $e
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response(Throwable $e): ResponseInterface
    {
        $details = new Inspector($e);

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
