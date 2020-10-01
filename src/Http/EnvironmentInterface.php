<?php

namespace Pavlakis\Cli\Http;

interface EnvironmentInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getProperties(): array;

    public function isAllowedMethod(string $method): bool;

    public function getRequestMethod(): string;

    public function getUri(): string;
}
