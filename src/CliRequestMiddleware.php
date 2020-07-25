<?php

declare(strict_types=1);

namespace Pavlakis\Cli;

use Pavlakis\Cli\Command\Input;
use Pavlakis\Cli\Command\InputInterface;
use Pavlakis\Cli\Http\EnvironmentInterface;
use Pavlakis\Cli\Http\EnvironmentProperties;
use Pavlakis\Cli\Http\Request\CliRequestFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CliRequestMiddleware implements Middleware
{
    /**
     * @var EnvironmentProperties
     */
    private $environment;

    /**
     * @var Command\InputInterface
     */
    private $input;

    public function __construct(?EnvironmentInterface $environment = null, ?InputInterface $input = null)
    {
        if (null === $input) {
            $input = Input::create();
        }
        $this->input = $input;

        if (null === $environment) {
            $environment = EnvironmentProperties::createFromInput($this->input);
        }

        $this->environment = $environment;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!$this->input->isVerified()) {
            return $handler->handle($request);
        }

        $method = $this->input->getArgument('method', '');
        if ($this->environment->isAllowedMethod($method)) {
            $request = CliRequestFactory::createFromEnvironment($this->environment);
        }

        return $handler->handle($request);
    }
}
