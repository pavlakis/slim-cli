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
     * @var UriInterface
     */
    private $uri;

    /**
     * @param UriInterface         $uri
     * @param array<string, mixed> $customProperties
     * @param array                $environmentProperties
     */
    public function __construct(
        UriInterface $uri,
        array $customProperties = [],
        array $environmentProperties = []
    ) {
        $this->uri = $uri;
        $this->mergeCustomProperties($customProperties);
        $this->overrideFromEnvironment($environmentProperties);
    }

    public static function createFromInput(InputInterface $input): ?EnvironmentInterface
    {
        $params = $input->getArgument('query', '');
        $uri = new Uri(
            $input->getArgument('path', ''),
            $params
        );

        $environmentProperties = [];
        if (null !== $input->getArgument('environment', null)) {
            $environmentProperties = \json_decode($input->getArgument('environment', ''), true);
        }

        $requestMethod = $input->getArgument('method', '');

        return new self(
            $uri,
            [
            'REQUEST_URI' => $uri->getUri(),
            'REQUEST_METHOD' => $requestMethod,
            'QUERY_STRING' => $params,
            ],
            $environmentProperties
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
    private function overrideFromEnvironment(array $environmentProperties = []): array
    {
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
        if (!\array_key_exists('REQUEST_METHOD', $this->properties)) {
            throw new \RuntimeException('Request method has not been passed.');
        }

        return $this->properties['REQUEST_METHOD'];
    }

    public function getUri(): string
    {
        return $this->properties['REQUEST_URI'] ?? $this->uri->getUri();
    }
}
