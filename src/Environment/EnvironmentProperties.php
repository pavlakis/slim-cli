<?php

namespace pavlakis\cli\Environment;

final class DefaultEnvironment implements EnvironmentInterface
{
    private $properties = [
        'REQUEST_METHOD'    => 'GET',
        'REQUEST_URI'       => '',
        'QUERY_STRING'      => ''
    ];

    /**
     * @param array $args
     * @return array
     */
    public function getProperties(array $args = [])
    {
        return $this->populate($args);
    }

    /**
     * @param array $args
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

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return 'GET';
    }
}
