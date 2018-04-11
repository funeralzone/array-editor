<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor;

interface ArrayEditor
{
    public function all(): array;
    public function &get(array $path);
    public function add(array $path, $newValue, $key = null): void;
    public function edit(array $path, $newValue): void;
    public function delete(array $path): void;
}
