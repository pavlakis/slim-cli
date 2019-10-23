<?php
/**
 * Pavlakis Slim CLI Request.
 *
 * @see        https://github.com/pavlakis/slim-cli
 *
 * @copyright   Copyright © 2019 Antonios Pavlakis
 * @author      Antonios Pavlakis
 * @license     https://github.com/pavlakis/slim-cli/blob/master/LICENSE (BSD 3-Clause License)
 */

namespace pavlakis\cli\Environment;

use pavlakis\cli\Exception\DefaultPropertyExistsException;

final class EnvironmentProperties implements EnvironmentPropertiesInterface
{
    private $properties = [
        'REQUEST_METHOD' => 'GET',
        'REQUEST_URI' => '',
        'QUERY_STRING' => '',
    ];

    /**
     * @param array $customProperties
     *
     * @throws DefaultPropertyExistsException
     */
    public function __construct(array $customProperties = [])
    {
        $this->mergeCustomProperties($customProperties);
    }

    /**
     * @param array $args
     *
     * @return array
     */
    public function getProperties(array $args = [])
    {
        return $this->populate($args);
    }

    /**
     * @return string
     *
     * @deprecated
     */
    public function getRequestMethod()
    {
        return 'GET';
    }

    /**
     * @param array $customProperties
     *
     * @throws DefaultPropertyExistsException
     */
    private function mergeCustomProperties(array $customProperties = [])
    {
        foreach ($customProperties as $customProperty => $value) {
            if (array_key_exists(strtoupper($customProperty), $this->properties)) {
                throw new DefaultPropertyExistsException(
                    sprintf('Cannot override default property for %s', $customProperty)
                );
            }

            unset($customProperties[$customProperty]);
            $customProperties[strtoupper($customProperty)] = $value;
        }

        $this->properties = array_merge($this->properties, $customProperties);
    }

    /**
     * @param array $args
     *
     * @return array
     */
    private function populate(array $args = [])
    {
        foreach ($args as $property => $value) {
            if (array_key_exists($property, $this->properties)) {
                $this->properties[strtoupper($property)] = $value;
            }
        }

        return $this->properties;
    }
}
