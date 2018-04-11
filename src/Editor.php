<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor;

use \Throwable;
use Funeralzone\ArrayEditor\Exceptions\ArrayIndexNotFound;
use Funeralzone\ArrayEditor\Exceptions\CallableRaisedError;
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

            $index = $this->findArrayItemIndex($scope, $pathItem);
            $scope = &$scope[$index];
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
            throw new PathIsNotAnArray();
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
            $index = $this->findArrayItemIndex($data, $targetPathItem);
            unset($data[$index]);
        } else {
            throw new PathIsNotAnArray();
        }

        return;
    }

    private function findArrayItemIndex(array $data, $element)
    {
        if (is_callable($element)) {
            try {
                $index = call_user_func($element, $data);
            } catch (Throwable $e) {
                throw new CallableRaisedError();
            }
        } else {
            $index = $element;
        }

        if ($index !== null) {
            if (array_key_exists($index, $data)) {
                return $index;
            } else {
                throw new PathDoesNotExist();
            }
        } else {
            throw new ArrayIndexNotFound();
        }
    }
}
