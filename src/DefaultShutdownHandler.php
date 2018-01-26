<?php declare(strict_types=1);

namespace Ellipse\Http;

use Psr\Http\Message\ServerRequestInterface;

use Ellipse\Http\Exceptions\Response\DefaultRequestBasedResponseFactory;

class DefaultShutdownHandler extends ShutdownHandler
{
    /**
     * Set up a shotdown handler with the given request and a default reponse
     * factory.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @param bool                                      $debug
     */
    public function __construct(ServerRequestInterface $request, bool $debug)
    {
        parent::__construct($request, new DefaultRequestBasedResponseFactory($debug));
    }
}
