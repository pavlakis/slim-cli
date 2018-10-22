<?php

namespace pavlakis\cli\Environment;


interface EnvironmentInterface
{
    /**
     * @param array $args
     * @return array
     */
    public function getProperties(array $args = []);

    /**
     * @return string
     */
    public function getRequestMethod();
}
