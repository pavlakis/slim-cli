<?php

declare(strict_types=1);

namespace Pavlakis\Cli\Command;

interface InputInterface
{
    /**
     * @param string      $argument
     * @param string|null $default
     *
     * @return string|null
     */
    public function getArgument(string $argument, ?string $default = null): ?string;

    public function isVerified(): bool;
}
