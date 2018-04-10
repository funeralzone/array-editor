<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor\Exceptions;

use \Exception;

class InvalidArrayIndex extends Exception
{
    public function __construct()
    {
        parent::__construct('An invalid array index was found');
    }
}
