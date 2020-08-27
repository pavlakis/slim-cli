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
     * @var EnvironmentInterface|null
     */
    private $environment;

    /**
     * @var Command\InputInterface
     */
    private $input;

    public function __construct(?EnvironmentInterface $environment = null, ?InputInterface $input = null)
    {
        if (null === $input) {
            $input = Input::createFromCli();
        }
        $this->input = $input;

        if (null === $environment) {
            $environment = EnvironmentProperties::createFromInput($this->input);
        }

        $this->environment = $environment;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!$this->input->hasInput()) {
            return $handler->handle($request);
        }

        $requestMethod = $this->getRequestMethod();
        if ($this->environment->isAllowedMethod($requestMethod)) {
            $request = CliRequestFactory::createFromEnvironment($this->environment);
        }

        return $handler->handle($request);
    }

    private function getRequestMethod(): string
    {
        if (null !== $this->environment) {
            return $this->environment->getRequestMethod();
        }

        return $this->input->getArgument('method', '');
    }
}
