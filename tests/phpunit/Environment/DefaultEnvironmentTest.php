<?php
/**
 * Pavlakis Slim CLI Request
 *
 * @link        https://github.com/pavlakis/slim-cli
 * @copyright   Copyright © 2018 Antonis Pavlakis
 * @author      Antonios Pavlakis
 * @license     https://github.com/pavlakis/slim-cli/blob/master/LICENSE (BSD 3-Clause License)
 */
namespace pavlakis\cli\tests\Environment;

use pavlakis\cli\Environment\DefaultEnvironment;


class DefaultEnvironmentTest extends \PHPUnit_Framework_TestCase
{

    public function testGetPropertiesWithEmptyArgumentsReturnsDefaultProperties()
    {
        $defaultProperties = [
            'REQUEST_METHOD'    => 'GET',
            'REQUEST_URI'       => '',
            'QUERY_STRING'      => ''
        ];

        static::assertSame($defaultProperties, (new DefaultEnvironment())->getProperties());
    }

    public function testGetPropertiesWithArgumentsReturnsUpdatedDefaultProperties()
    {
        $defaultProperties = [
            'REQUEST_METHOD'    => 'GET',
            'REQUEST_URI'       => '/status',
            'QUERY_STRING'      => 'event=true'
        ];

        static::assertSame($defaultProperties, (new DefaultEnvironment())->getProperties([
            'REQUEST_URI'       => '/status',
            'QUERY_STRING'      => 'event=true'
        ]));
    }

    public function testGetGetRequestMethod()
    {
        static::assertSame('GET', (new DefaultEnvironment())->getRequestMethod());
    }
}
