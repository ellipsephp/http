<?php declare(strict_types=1);

namespace Ellipse\Http\Handlers;

use Throwable;

use Psr\Http\Server\RequestHandlerInterface;

use Negotiation\Negotiator;
use League\Plates\Engine;

use Ellipse\Http\Middleware\JsonHandlerMiddleware;
use Ellipse\Dispatcher\RequestHandlerWithMiddleware;

class ExceptionRequestHandlerFactory
{
    /**
     * The templates path.
     *
     * @var string
     */
    private $path;

    /**
     * Whether the application is in debug mode or not.
     *
     * @var bool
     */
    private $debug;

    /**
     * Set up an exeception request handler factory with the given templates
     * path and debug status.
     *
     * @param string    $path
     * @param bool      $debug
     */
    public function __construct(string $path, bool $debug)
    {
        $this->path = $path;
        $this->debug = $debug;
    }

    /**
     * Return an exception request handler producing a response for the given
     * exception.
     *
     * @param \Throwable $e
     * @return \Psr\Http\Server\RequestHandlerInterface;
     */
    public function __invoke(Throwable $e): RequestHandlerInterface
    {
        $negotiator = new Negotiator;
        $priorities = ['text/html', 'application/json'];

        $engine = new Engine($this->path);

        $json = $this->debug
            ? new JsonDetailledExceptionRequestHandler($e)
            : new JsonSimpleExceptionRequestHandler;

        $html = $this->debug
            ? new HtmlDetailledExceptionRequestHandler($engine, $e)
            : new HtmlSimpleExceptionRequestHandler($engine);


        return new RequestHandlerWithMiddleware($html,
            new JsonHandlerMiddleware($negotiator, $priorities, $json)
        );
    }
}
