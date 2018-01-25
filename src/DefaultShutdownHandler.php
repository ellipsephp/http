<?php declare(strict_types=1);

namespace Ellipse\Http;

use function Http\Response\send;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Http\Exceptions\Response\DefaultRequestBasedResponseFactory;

class DefaultShutdownHandler extends ShutdownHandler
{
    public function __construct(ServerRequestInterface $request, bool $debug)
    {
        parent::__construct($request, new DefaultRequestBasedResponseFactory($debug));
    }
}
