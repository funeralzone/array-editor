<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor\Exceptions;

use \Exception;

class PathDoesNotExist extends Exception
{
    public function __construct(array $path)
    {
        parent::__construct(sprintf('Path "%s" does not exist', implode('/', $path)));
    }
}
