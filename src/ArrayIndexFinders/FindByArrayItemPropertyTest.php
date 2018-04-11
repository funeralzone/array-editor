<?php

// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Funeralzone\ArrayEditor;

use Funeralzone\ArrayEditor\ArrayIndexFinders\FindByArrayItemProperty;
use PHPUnit\Framework\TestCase;

final class FindByArrayItemPropertyTest extends TestCase
{
    public function test_finder_returns_expected_index()
    {
        $data = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3]
        ];

        $finder = new FindByArrayItemProperty('id', 2);

        $index = $finder->findArrayIndex($data);

        $this->assertEquals(1, $index);
    }

    public function test_finder_fails_to_return_index_from_invalid_data()
    {
        $data = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3]
        ];

        $finder = new FindByArrayItemProperty('key', 2);

        $index = $finder->findArrayIndex($data);

        $this->assertNull($index);
    }

    public function test_finder_fails_to_return_index_from_missing_data()
    {
        $data = [
            ['id' => 1],
            ['id' => 3]
        ];

        $finder = new FindByArrayItemProperty('id', 2);

        $index = $finder->findArrayIndex($data);

        $this->assertNull($index);
    }
}
