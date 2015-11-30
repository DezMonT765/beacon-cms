<?php

namespace app\components\geometry;

use Exception;

class IllegalArgumentException extends Exception
{
    public function __consruct()
    {
        $this->message = "Illegal argument";
    }
}
?>