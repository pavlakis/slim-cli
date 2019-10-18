<?php
/**
 * Pavlakis Slim CLI Request.
 *
 * @see        https://github.com/pavlakis/slim-cli
 *
 * @copyright   Copyright © 2018 Antonis Pavlakis
 * @author      Antonios Pavlakis
 * @license     https://github.com/pavlakis/slim-cli/blob/master/LICENSE (BSD 3-Clause License)
 */

namespace pavlakis\cli\tests\Environment;

use pavlakis\cli\Environment\EnvironmentProperties;

/**
 * @internal
 */
class EnvironmentPropertiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws \pavlakis\cli\Exception\DefaultPropertyExistsException
     */
    public function testGetPropertiesWithEmptyArgumentsReturnsDefaultProperties()
    {
        $defaultProperties = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '',
            'QUERY_STRING' => '',
        ];

        static::assertSame($defaultProperties, (new EnvironmentProperties())->getProperties());
    }

    /**
     * @throws \pavlakis\cli\Exception\DefaultPropertyExistsException
     */
    public function testGetPropertiesWithArgumentsReturnsUpdatedDefaultProperties()
    {
        $defaultProperties = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/status',
            'QUERY_STRING' => 'event=true',
        ];

        static::assertSame($defaultProperties, (new EnvironmentProperties())->getProperties([
            'REQUEST_URI' => '/status',
            'QUERY_STRING' => 'event=true',
        ]));
    }

    /**
     * @throws \pavlakis\cli\Exception\DefaultPropertyExistsException
     */
    public function testGetPropertiesWithCustomProperty()
    {
        $defaultProperties = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/status',
            'QUERY_STRING' => 'event=true',
            'SERVER_PORT' => 9000,
        ];

        static::assertSame($defaultProperties, (new EnvironmentProperties(['SERVER_PORT' => 9000]))->getProperties([
            'REQUEST_URI' => '/status',
            'QUERY_STRING' => 'event=true',
        ]));
    }

    /**
     * @expectedException \pavlakis\cli\Exception\DefaultPropertyExistsException
     */
    public function testMergeCustomPropertiesPassingDefaultPropertyThrowsException()
    {
        new EnvironmentProperties(['REQUEST_METHOD' => 'POST']);
    }

    /**
     * @throws \pavlakis\cli\Exception\DefaultPropertyExistsException
     */
    public function testGetGetRequestMethod()
    {
        static::assertSame('GET', (new EnvironmentProperties())->getRequestMethod());
    }
}
