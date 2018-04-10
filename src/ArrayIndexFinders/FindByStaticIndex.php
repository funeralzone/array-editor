<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor\ArrayIndexFinders;

class FindByStaticIndex implements Finder
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function findArrayIndex(array $items)
    {
        return $this->key;
    }
}
