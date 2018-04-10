<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor;

use Funeralzone\ArrayEditor\ArrayIndexFinders\Finder;
use Funeralzone\ArrayEditor\Exceptions\ArrayIndexNotFound;
use Funeralzone\ArrayEditor\Exceptions\InvalidArrayIndex;
use Funeralzone\ArrayEditor\Exceptions\PathDoesNotExist;
use Funeralzone\ArrayEditor\Exceptions\PathIsNotAnArray;

final class Editor
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function all(): array
    {
        return $this->data;
    }

    public function &get(array $path)
    {
        $scope = &$this->data;
        $pathElementsToIterateOver = $path;
        while ($pathElementsToIterateOver) {
            $pathItem = array_shift($pathElementsToIterateOver);

            if ($pathItem instanceof Finder) {
                $index = $this->findArrayItemIndex($scope, $pathItem);
                $scope = &$scope[$index];
            } else {
                if (is_array($scope) && array_key_exists($pathItem, $scope)) {
                    $scope = &$scope[$pathItem];
                } else {
                    throw new PathDoesNotExist($path);
                }
            }
        }

        return $scope;
    }

    public function edit(array $path, $newValue): void
    {
        $data = &$this->get($path);
        $data = $newValue;

        return;
    }

    public function insertArrayItem(array $path, $newValue, $index = null): void
    {
        $data = &$this->get($path);
        if (is_array($data)) {
            if ($index) {
                $data[$index] = $newValue;
            } else {
                $data[] = $newValue;
            }
        } else {
            throw new PathIsNotAnArray($path);
        }

        return;
    }

    public function editArrayItem(array $parentPath, $newValue): void
    {
        $data = &$this->get($parentPath);
        $data = $newValue;

        return;
    }

    public function deleteArrayItem(array $parentPath, Finder $arrayIndexFinder): void
    {
        $data = &$this->get($parentPath);
        if (is_array($data)) {
            $index = $this->findArrayItemIndex($data, $arrayIndexFinder);
            unset($data[$index]);
        } else {
            throw new PathIsNotAnArray($parentPath);
        }

        return;
    }

    private function findArrayItemIndex(array $data, Finder $arrayIndexFinder)
    {
        $index = $arrayIndexFinder->findArrayIndex($data);
        if ($index !== null) {
            if (array_key_exists($index, $data)) {
                return $index;
            } else {
                throw new InvalidArrayIndex();
            }
        } else {
            throw new ArrayIndexNotFound();
        }
    }
}
