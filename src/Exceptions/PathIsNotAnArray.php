<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor\Exceptions;

use \Exception;

class PathIsNotAnArray extends Exception
{
    public function __construct(array $path)
    {
        parent::__construct(sprintf('The value at "%s" is not an array', implode('/', $path)));
    }
}
