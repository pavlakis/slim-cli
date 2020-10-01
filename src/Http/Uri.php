<?php

declare(strict_types=1);

namespace Pavlakis\Cli\Http;

final class Uri implements UriInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $params;

    public function __construct(string $path, string $params)
    {
        $this->path = $path;
        $this->params = $params;
    }

    public function getUri(): string
    {
        $uri = '/';
        if ('' !== $this->path) {
            $uri = $this->path;
        }

        if ('' !== $this->params) {
            $uri .= '?'.$this->params;
        }

        return $uri;
    }
}
