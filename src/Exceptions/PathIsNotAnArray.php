<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor\Exceptions;

use \Exception;

class PathIsNotAnArray extends Exception
{
    public function __construct()
    {
        parent::__construct('The path is not an array');
    }
}
