<?php

declare(strict_types=1);

namespace pavlakis\cli\Command;

interface InputInterface
{
    /**
     * @param string $argument
     *
     * @return string|null
     */
    public function getArgument(string $argument): ?string;
}
