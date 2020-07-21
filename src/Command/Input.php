<?php

declare(strict_types=1);

namespace Pavlakis\Cli\Command;

/**
 * Usage: php public/index.php -m=GET -p=/ -q=test=123
 *
 * -m (method get, post, etc) defaults to ‘get’
 * -q (query parameters)
 * -d (data for a post??)
 * -c (content eg json string)
 * -p (path eg /events ) - required
 */
final class Input implements InputInterface
{
    private const SHORT_OPTIONS = 'm::q::d::c::h::p:';

    private const LONG_OPTIONS = [
        'method::',
        'query::',
        'data::',
        'content::',
        'header::',
        'path:',
    ];

    private const OPTIONS = [
        'method' => 'm',
        'query' => 'q',
        'data' => 'd',
        'content' => 'c',
        'header' => 'h',
        'path' => 'p',
    ];

    private $values;

    /**
     * @param array<string, string> $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public static function create(): InputInterface
    {
        return new self(
            \getopt(self::SHORT_OPTIONS, self::LONG_OPTIONS)
        );
    }

    public function hasInput(): bool
    {
        return 0 !== count($this->values)
            && (isset($this->values['m']) || isset($this->values['method']));
    }

    public function getArgument(string $argument): ?string
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

        return null;
    }
}
