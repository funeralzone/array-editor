<?php

// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Funeralzone\ArrayEditor;

use Funeralzone\ArrayEditor\ArrayIndexFinders\Finder;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

final class EditorTest extends TestCase
{
    // TODO - test exceptions

    /** @var Editor $editor */
    private $editor;

    public function setUp()
    {
        parent::setUp();

        $this->editor = new Editor([
            'simpleValue' => 'ROOT VALUE',
            'simpleArray' => [
                'subValue' => 'SUB VALUE'
            ],
            'finderArray' => [
                [
                    'id' => 1,
                    'simpleValue' => 'ONE'
                ],
                [
                    'id' => 2,
                    'simpleValue' => 'TWO'
                ],
                [
                    'id' => 3,
                    'simpleValue' => 'THREE'
                ],
            ]
        ]);
    }

    public function test_simple_get_from_root()
    {
        $testEditor = $this->editor;
        $data = $testEditor->get(
            [
                'simpleValue'
            ]
        );

        $this->assertEquals('ROOT VALUE', $data);
    }

    public function test_simple_get_from_nested()
    {
        $testEditor = $this->editor;
        $data = $testEditor->get(
            [
                'simpleArray',
                'subValue'
            ]
        );

        $this->assertEquals('SUB VALUE', $data);
    }

    public function test_finder_get_from_root()
    {
        $mockFinder = \Mockery::mock(Finder::class);
        $mockFinder->shouldReceive('findArrayIndex')
            ->times(1)
            ->andReturn('simpleValue');

        $testEditor = $this->editor;
        $data = $testEditor->get(
            [
                $mockFinder
            ]
        );

        $this->assertEquals('ROOT VALUE', $data);
    }

    public function test_finder_get_from_nested()
    {
        $mockFinder = \Mockery::mock(Finder::class);
        $mockFinder->shouldReceive('findArrayIndex')
            ->times(1)
            ->andReturn('subValue');

        $testEditor = $this->editor;
        $data = $testEditor->get(
            [
                'simpleArray',
                $mockFinder
            ]
        );

        $this->assertEquals('SUB VALUE', $data);
    }

    public function test_simple_add_to_root()
    {
        $testEditor = $this->editor;
        $testEditor->add(
            [],
            'simpleValue',
            'key'
        );

        $data = $testEditor->all();
        $this->assertArrayHasKey('key', $data);
        $this->assertEquals('simpleValue', $data['key']);
    }

    public function test_simple_add_to_nested_array()
    {
        $expectedValue = 'VALUE';

        $testEditor = $this->editor;
        $testEditor->add(
            [
                'simpleArray'
            ],
            $expectedValue,
            'key'
        );

        $data = $testEditor->all();
        $this->assertArrayHasKey('simpleArray', $data);
        $this->assertArrayHasKey('key', $data['simpleArray']);
        $this->assertEquals($expectedValue, $data['simpleArray']['key']);
    }

    public function test_simple_edit_root_item()
    {
        $expectedValue = 'UPDATED';

        $testEditor = $this->editor;
        $testEditor->edit(
            [
                'simpleValue'
            ],
            $expectedValue
        );

        $data = $testEditor->all();
        $this->assertArrayHasKey('simpleValue', $data);
        $this->assertEquals($expectedValue, $data['simpleValue']);
    }

    public function test_simple_edit_nested_item()
    {
        $expectedValue = 'UPDATED';

        $testEditor = $this->editor;
        $testEditor->edit(
            [
                'simpleArray',
                'subValue'
            ],
            $expectedValue
        );

        $data = $testEditor->all();
        $this->assertArrayHasKey('simpleArray', $data);
        $this->assertArrayHasKey('subValue', $data['simpleArray']);
        $this->assertEquals($expectedValue, $data['simpleArray']['subValue']);
    }

    public function test_simple_delete_root_item()
    {
        $testEditor = $this->editor;
        $testEditor->delete(
            [
                'simpleValue'
            ]
        );

        $data = $testEditor->all();
        $this->assertCount(2, $data);
        $this->assertArrayNotHasKey('simpleValue', $data);
    }

    public function test_simple_delete_nested_item()
    {
        $testEditor = $this->editor;
        $testEditor->delete(
            [
                'simpleArray',
                'subValue'
            ]
        );

        $data = $testEditor->all();
        $this->assertArrayHasKey('simpleArray', $data);
        $this->assertCount(0, $data['simpleArray']);
        $this->assertArrayNotHasKey('subValue', $data['simpleArray']);
    }
}