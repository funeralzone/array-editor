<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor\Exceptions;

use \Exception;

class ArrayIndexNotFound extends Exception
{
    public function __construct()
    {
        parent::__construct('An array index could not be found');
    }
}
