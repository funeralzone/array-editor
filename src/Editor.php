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

    public function add(array $path, $newValue, $key = null): void
    {
        $data = &$this->get($path);
        if (is_array($data)) {
            if ($key) {
                $data[$key] = $newValue;
            } else {
                $data[] = $newValue;
            }
        } else {
            throw new PathIsNotAnArray($path);
        }

        return;
    }

    public function edit(array $path, $newValue): void
    {
        $data = &$this->get($path);
        $data = $newValue;

        return;
    }

    public function delete(array $path): void
    {
        $targetPathItem = array_pop($path);
        if (count($path)) {
            $data = &$this->get($path);
        } else {
            $data = &$this->data;
        }

        if (is_array($data)) {
            if ($targetPathItem instanceof Finder) {
                $index = $this->findArrayItemIndex($data, $targetPathItem);
            } else {
                $index = $targetPathItem;
            }

            if (array_key_exists($index, $data)) {
                unset($data[$index]);
            } else {
                throw new InvalidArrayIndex();
            }
        } else {
            throw new PathIsNotAnArray($path);
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
