<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor\ArrayIndexFinders;

class FindByArrayItemProperty
{
    private $propertyName;
    private $propertyValue;

    public function __construct($propertyName, $propertyValue)
    {
        $this->propertyName = $propertyName;
        $this->propertyValue = $propertyValue;
    }

    public function __invoke(array $items)
    {
        $matchingIndex = null;
        foreach ($items as $index => $item) {
            if (is_array($item)) {
                if (array_key_exists($this->propertyName, $item)) {
                    if ($item[$this->propertyName] == $this->propertyValue) {
                        $matchingIndex = $index;
                        break;
                    }
                }
            }
        }
        return $matchingIndex;
    }
}
