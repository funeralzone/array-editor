<?php

declare(strict_types=1);

namespace Funeralzone\ArrayEditor\Exceptions;

use \Exception;

class PathDoesNotExist extends Exception
{
    public function __construct()
    {
        parent::__construct('Path does not exist');
    }
}
