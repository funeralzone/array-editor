<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor\ArrayIndexFinders;

interface Finder
{
    public function findArrayIndex(array $items);
}