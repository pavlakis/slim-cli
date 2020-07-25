<?php

namespace Pavlakis\Cli\Http;

interface EnvironmentInterface
{
    public function getProperties(): array;

    public function isAllowedMethod(string $method): bool;

    public function getRequestMethod(): string;

    public function getUri(): string;
}
