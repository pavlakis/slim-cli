<?php

declare(strict_types=1);

namespace Pavlakis\Cli\Command;

final class Input implements InputInterface
{
    private const SHORT_OPTIONS = 'm::q::d::c::h::e::p::';

    private const LONG_OPTIONS = [
        'method::',
        'query::',
        'data::',
        'content::',
        'header::',
        'path::',
        'environment::',
    ];

    private const OPTIONS = [
        'method' => 'm',
        'query' => 'q',
        'data' => 'd',
        'content' => 'c',
        'header' => 'h',
        'path' => 'p',
        'environment' => 'e',
    ];

    /**
     * @var array<string, mixed>
     */
    private $values;

    /**
     * @param array<string, mixed> $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public static function createFromCli(): InputInterface
    {
        $values = \getopt(self::SHORT_OPTIONS, self::LONG_OPTIONS);
        if (!is_array($values)) {
            $values = [];
        }

        return new self($values);
    }

    public function hasInput(): bool
    {
        return 0 !== \count($this->values);
    }

    public function getArgument(string $argument, ?string $default = null): ?string
    {
        if (!array_key_exists($argument, self::OPTIONS) && !in_array($argument, self::OPTIONS)) {
            throw new \InvalidArgumentException(\sprintf('The argument "%s" does not exist', $argument));
        }

        if (isset($this->values[$argument])) {
            return $this->values[$argument];
        }

        if (isset(self::OPTIONS[$argument], $this->values[self::OPTIONS[$argument]])) {
            return $this->values[self::OPTIONS[$argument]];
        }

        return $default;
    }
}
