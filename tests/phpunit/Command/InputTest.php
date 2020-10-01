<?php

declare(strict_types=1);

namespace Pavlakis\Cli\Test\Command;

use Pavlakis\Cli\Command\Input;
use PHPUnit\Framework\TestCase;

final class InputTest extends TestCase
{
    /**
     * @test
     */
    public function get_argument_with_invalid_argument_throws_invalid_argument_exception(): void
    {
        $input = new Input(['unknown' => 'stuff']);

        $this->expectException(\InvalidArgumentException::class);

        $input->getArgument('unknown');
    }

    /**
     * @test
     * @dataProvider getAllAttributesDataProvider
     *
     * @param string $argument
     */
    public function get_missing_argument_will_return_null(string $argument): void
    {
        $input = new Input([]);
        $this->assertNull($input->getArgument($argument));
    }

    public function getAllAttributesDataProvider(): array
    {
        return [
            ['method'],
            ['query'],
            ['data'],
            ['content'],
            ['header'],
            ['path'],
            ['m'],
            ['q'],
            ['d'],
            ['c'],
            ['h'],
            ['p'],
        ];
    }

    /**
     * @test
     * @dataProvider getMinimumArgumentsDataProvider
     * @param array $values
     */
    public function has_input_needs_method_argument_as_a_minimum(array $values): void
    {
        $input = new Input($values);
        $this->assertTrue($input->hasInput());
    }

    public function getMinimumArgumentsDataProvider(): array
    {
        return [
            [
                ['m' => 'GET']
            ],
            [
                ['method' => 'GET']
            ],
        ];
    }
}
