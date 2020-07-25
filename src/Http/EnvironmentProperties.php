<?php

declare(strict_types=1);

namespace Pavlakis\Cli\Http;

use Slim\Psr7\Environment;
use Pavlakis\Cli\Command\InputInterface;

final class EnvironmentProperties implements EnvironmentInterface
{
    private const ALLOWED_METHODS = [
        'GET',
        'POST',
        'PATCH',
        'PUT',
        'DELETE',
    ];

    private $properties = [
        'HTTP_USER_AGENT' => 'Slim CLI',
    ];

    /**
     * @var string
     */
    private $requestMethod;

    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * @param UriInterface         $uri
     * @param array<string, mixed> $customProperties
     * @param string               $requestMethod
     */
    public function __construct(UriInterface $uri, array $customProperties = [], string $requestMethod = '')
    {
        $this->uri = $uri;
        $this->requestMethod = $requestMethod;
        // todo - use populate() method
        $this->mergeCustomProperties($customProperties);
    }

    public static function createFromInput(InputInterface $input): ?EnvironmentInterface
    {
        $params = $input->getArgument('query', '');
        $uri = new Uri(
            $input->getArgument('path', ''),
            $params
        );

        return new self(
            $uri,
            [
            'REQUEST_URI' => $uri->getUri(),
            'QUERY_STRING' => $params,
            ],
            $params = $input->getArgument('method', '')
        );
    }

    /**
     * @param array<string, mixed> $customProperties
     */
    private function mergeCustomProperties(array $customProperties = []): void
    {
        $this->properties = array_merge(Environment::mock($customProperties), $this->properties);
    }

    /**
     * Populate arguments as passed through specific flags.
     * The environment override will take precedence and override with all passed arguments as they are.
     * e.g. -e=REQUEST_SCHEME=https,SERVER_PORT=443.
     *
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param array $args
     * @param array $environmentProperties
     *
     * @return array
     */
    private function populate(array $args = [], array $environmentProperties = []): array
    {
        foreach ($args as $property => $value) {
            if (array_key_exists($property, $this->properties)) {
                $this->properties[strtoupper($property)] = $value;
            }
        }

        foreach ($environmentProperties as $property => $value) {
            $property = strtoupper($property);
            if ('SERVER_PORT' === $property) {
                $value = (int) $value;
            }

            $this->properties[$property] = $value;
        }

        return $this->properties;
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    public function isAllowedMethod(string $method): bool
    {
        return \in_array(strtoupper($method), self::ALLOWED_METHODS);
    }

    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    public function getUri(): string
    {
        return $this->uri->getUri();
    }
}
