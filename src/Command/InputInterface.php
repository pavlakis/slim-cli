<?php

declare(strict_types=1);

namespace Pavlakis\Cli\Command;

interface InputInterface
{
    /**
     * @param string $argument
     *
     * @return string|null
     */
    public function getArgument(string $argument): ?string;

    public function hasInput(): bool;
}
