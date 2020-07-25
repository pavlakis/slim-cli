<?php

declare(strict_types=1);

namespace Pavlakis\Cli\Command;

/**
 * Usage:
 * PAVLAKIS_SLIM_CLI_REQUEST=true php public/index.php --method=GET --query=slim-cli=itWorks!
 *
 * Using a script, define PAVLAKIS_SLIM_CLI_REQUEST
 *
 * -m (method get, post, etc) defaults to ‘get’
 * -q (query parameters)
 * -d (data for a post??)
 * -c (content eg json string)
 * -p (path eg /events ) -
 */
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
        'environment' => 'e', // pass any request parameters as a CSV
    ];

    /**
     * @var array
     */
    private $values;

    /**
     * @var bool
     */
    private $verified;

    /**
     * @param array<string, string> $values
     * @param bool                  $verified
     */
    private function __construct(array $values, bool $verified)
    {
        $this->values = $values;
        $this->verified = $verified;
    }

    public static function create(): InputInterface
    {
        $values = \getopt(self::SHORT_OPTIONS, self::LONG_OPTIONS);
        if (false === $values) {
            $values = [];
        }

        return new self($values, \array_key_exists('PAVLAKIS_SLIM_CLI_REQUEST', $_SERVER));
    }

    public function isVerified(): bool
    {
        return $this->verified;
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
