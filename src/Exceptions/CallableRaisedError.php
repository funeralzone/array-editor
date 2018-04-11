<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor\Exceptions;

use \Exception;

class CallableRaisedError extends Exception
{
    public function __construct()
    {
        parent::__construct('An error or exception was encountered whilst resolving a callable path element');
    }
}
