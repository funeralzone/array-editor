<?php

// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Funeralzone\ArrayEditor;

use Funeralzone\ArrayEditor\Exceptions\ArrayIndexNotFound;
use Funeralzone\ArrayEditor\Exceptions\PathDoesNotExist;
use Funeralzone\ArrayEditor\Exceptions\PathIsNotAnArray;
use PHPUnit\Framework\TestCase;

final class DefaultEditorTest extends TestCase
{
    public function test_simple_get_from_root()
    {
        $testEditor = new DefaultArrayEditor([
            'simpleValue' => 'ROOT VALUE'
        ]);

        $data = $testEditor->get(['simpleValue']);
        $this->assertEquals('ROOT VALUE', $data);
    }

    public function test_simple_get_from_nested()
    {
        $testEditor = new DefaultArrayEditor([
            'simpleArray' => [
                'subValue' => 'SUB VALUE'
            ]
        ]);
        $data = $testEditor->get(['simpleArray','subValue']);
        $this->assertEquals('SUB VALUE', $data);
    }

    public function test_finder_get_from_root()
    {
        $testEditor = new DefaultArrayEditor([
            'simpleValue' => 'ROOT VALUE'
        ]);
        $data = $testEditor->get([
            function() {
                return 'simpleValue';
            }
        ]);
        $this->assertEquals('ROOT VALUE', $data);
    }

    public function test_finder_get_from_nested()
    {
        $testEditor = new DefaultArrayEditor([
            'simpleArray' => [
                'subValue' => 'SUB VALUE'
            ]
        ]);
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
        $testEditor = new DefaultArrayEditor([]);
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
        $testEditor = new DefaultArrayEditor([
            'simpleArray' => [
                'subValue' => 'SUB VALUE'
            ]
        ]);

        $expectedValue = 'VALUE';
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

    public function test_simple_replace_root_item()
    {
        $testEditor = new DefaultArrayEditor([
            'simpleValue' => 'ROOT VALUE'
        ]);

        $expectedValue = 'UPDATED';

        $testEditor->replace(
            [
                'simpleValue'
            ],
            $expectedValue
        );

        $data = $testEditor->all();
        $this->assertArrayHasKey('simpleValue', $data);
        $this->assertEquals($expectedValue, $data['simpleValue']);
    }

    public function test_simple_replace_nested_item()
    {
        $testEditor = new DefaultArrayEditor([
            'simpleArray' => [
                'subValue' => 'SUB VALUE'
            ]
        ]);

        $expectedValue = 'UPDATED';

        $testEditor->replace(
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

    public function test_simple_merge_root_item()
    {
        $testEditor = new DefaultArrayEditor([
            'valueArray' => [
                'one' => 'first',
                'two' => 'second'
            ]
        ]);

        $newData = [
            'two' => 'updated',
            'three' => 'third'
        ];

        $testEditor->merge(['valueArray'], $newData);

        $this->assertCount(3, $testEditor->get(['valueArray']));
        $this->assertEquals($testEditor->get(['valueArray', 'one']), 'first');
        $this->assertEquals($testEditor->get(['valueArray', 'two']), 'updated');
        $this->assertEquals($testEditor->get(['valueArray', 'three']), 'third');
    }

    public function test_simple_delete_root_item()
    {
        $testEditor = new DefaultArrayEditor([
            'simpleValue' => 'ROOT VALUE'
        ]);

        $testEditor->delete(
            [
                'simpleValue'
            ]
        );

        $data = $testEditor->all();
        $this->assertCount(0, $data);
        $this->assertArrayNotHasKey('simpleValue', $data);
    }

    public function test_simple_delete_nested_item()
    {
        $testEditor = new DefaultArrayEditor([
            'simpleArray' => [
                'subValue' => 'SUB VALUE'
            ]
        ]);

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
        $testEditor = new DefaultArrayEditor([]);
        $this->expectException(PathDoesNotExist::class);
        $testEditor->get([
            'invalid_path'
        ]);
    }

    public function test_add_to_non_array_throws_pathisnotarray_exception()
    {
        $testEditor = new DefaultArrayEditor([
            'simpleArray' => [
                'subValue' => 'SUB VALUE'
            ]
        ]);
        $this->expectException(PathIsNotAnArray::class);
        $testEditor->add(
            [
                'simpleArray',
                'subValue'
            ],
            'value'
        );
    }

    public function test_dynamic_element_failing_to_find_index_throws_arrayindexnotfound_exception()
    {
        $testEditor = new DefaultArrayEditor([
            'simpleArray' => [
                'subValue' => 'SUB VALUE'
            ]
        ]);
        $this->expectException(ArrayIndexNotFound::class);
        $testEditor->get(
            [
                'simpleArray',
                function () {
                    return null;
                }
            ]
        );
    }
}
