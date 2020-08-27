<?php

declare(strict_types=1);

namespace Pavlakis\Cli\Test\Http;

use Pavlakis\Cli\Http\Uri;
use PHPUnit\Framework\TestCase;

final class UriTest extends TestCase
{
    /**
     * @test
     * @dataProvider getUriDataProvider
     *
     * @param string $path
     * @param string $params
     * @param string $expected
     */
    public function get_uri_with_different_arguments(string $path, string $params, string $expected): void
    {
        $uri = new Uri('', '');

        $this->assertSame('/', $uri->getUri());
    }

    public function getUriDataProvider(): array
    {
        return [
            ['', '', '/'],
            ['/', '', '/'],
            ['events', '', '/events'],
            ['/events', '', '/events'],
            ['/events', 'p1=1&p2=2', '/events?p1=1&p2=2'],
        ];
    }
}
