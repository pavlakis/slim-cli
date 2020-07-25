<?php

declare(strict_types=1);

namespace Pavlakis\Cli\Http\Request;

use Pavlakis\Cli\Http\EnvironmentInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

class CliRequestFactory
{
    public static function createFromEnvironment(EnvironmentInterface $environment): ServerRequestInterface
    {
        return (new ServerRequestFactory())
            ->createServerRequest(
                $environment->getRequestMethod(),
                $environment->getUri(),
                $environment->getProperties()
            );
    }
}
