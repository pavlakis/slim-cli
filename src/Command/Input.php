<?php

declare(strict_types=1);

namespace pavlakis\cli\Command;

/**
 * -m (method get, post, etc) defaults to ‘get’
 * -q (query parameters)
 * -d (data for a post??)
 * -c (content eg json string)
 * -p (path eg /events ) - required
 */
final class Input implements InputInterface
{
    private const SHORT_OPTIONS = 'm::q::d::c::ct::p:';

    private const LONG_OPTIONS = [
        'method::',
        'query::',
        'data::',
        'content::',
        'content-type::',
        'path:',
    ];

    private const OPTIONS = [
        'method' => 'm',
        'query' => 'q',
        'data' => 'd',
        'content' => 'c',
        'content-type' => 'ct',
        'path' => 'p',
    ];

    private $options;

    public function __construct()
    {
        $this->options = \getopt(self::SHORT_OPTIONS, self::LONG_OPTIONS);
    }

    public function getArgument(string $argument): ?string
    {
        if (!array_key_exists($argument, self::OPTIONS) && !in_array($argument, self::OPTIONS)) {
            throw new \InvalidArgumentException(\sprintf('The argument [%s] does not exist', $argument));
        }

        if (isset($this->options[$argument])) {
            return $this->options[$argument];
        }

        if (isset($this->options[self::OPTIONS[$argument]])) {
            return $this->options[self::OPTIONS[$argument]];
        }

        return null;
    }
}
