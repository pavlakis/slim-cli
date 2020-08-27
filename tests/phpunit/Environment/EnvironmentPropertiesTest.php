<?php

namespace Pavlakis\Cli\Test\Environment;

use Pavlakis\Cli\Command\Input;
use PHPUnit\Framework\TestCase;
use Pavlakis\Cli\Http\EnvironmentProperties;

class EnvironmentPropertiesTest extends TestCase
{
    public function testGetPropertiesWithEmptyArgumentsReturnsDefaultProperties(): void
    {
        $input = new Input([]);

        $properties = EnvironmentProperties::createFromInput($input)->getProperties();
        $this->assertSame('/', $properties['REQUEST_URI']);
        $this->assertSame('', $properties['REQUEST_METHOD']);
    }

    public function testGetPropertiesWithArgumentsReturnsUpdatedDefaultProperties(): void
    {
        $input = new Input(['method' => 'GET', 'path' => '/status', 'query' => 'event=true']);

        $properties = EnvironmentProperties::createFromInput($input)->getProperties();
        $this->assertSame('GET', $properties['REQUEST_METHOD']);
        $this->assertSame('/status?event=true', $properties['REQUEST_URI']);
        $this->assertSame('event=true', $properties['QUERY_STRING']);
    }

    public function testGetPropertiesWithCustomProperty(): void
    {
        $input = new Input(['method' => 'GET', 'path' => '/status', 'query' => 'event=true', 'environment' => '{"SERVER_PORT":9000}']);

        $properties = EnvironmentProperties::createFromInput($input)->getProperties();
        $this->assertSame('GET', $properties['REQUEST_METHOD']);
        $this->assertSame('/status?event=true', $properties['REQUEST_URI']);
        $this->assertSame('event=true', $properties['QUERY_STRING']);
        $this->assertSame(9000, $properties['SERVER_PORT']);
    }
}
