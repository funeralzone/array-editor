<?php

// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Funeralzone\ArrayEditor;

use Funeralzone\ArrayEditor\Exceptions\ArrayIndexNotFound;
use Funeralzone\ArrayEditor\Exceptions\PathDoesNotExist;
use Funeralzone\ArrayEditor\Exceptions\PathIsNotAnArray;
use PHPUnit\Framework\TestCase;

final class EditorTest extends TestCase
{
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
        $testEditor = $this->editor;
        $data = $testEditor->get(
            [
                function() {
                    return 'simpleValue';
                }
            ]
        );

        $this->assertEquals('ROOT VALUE', $data);
    }

    public function test_finder_get_from_nested()
    {
        $testEditor = $this->editor;
        $data = $testEditor->get(
            [
                'simpleArray',
                function() {
                    return 'subValue';
                }
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

    public function test_nonexistant_path_throws_pathdoesnotexist_exception()
    {
        $this->expectException(PathDoesNotExist::class);
        $this->editor->get([
            'invalid_path'
        ]);
    }

    public function test_add_to_non_array_throws_pathisnotarray_exception()
    {
        $this->expectException(PathIsNotAnArray::class);
        $this->editor->add(
            [
                'simpleArray',
                'subValue'
            ],
            'value'
        );
    }

    public function test_dynamic_element_failing_to_find_index_throws_arrayindexnotfound_exception()
    {
        $this->expectException(ArrayIndexNotFound::class);

        $this->editor->get(
            [
                'simpleArray',
                function () {
                    return null;
                }
            ]
        );
    }
}
