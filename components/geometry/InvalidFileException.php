<?php
namespace app\components\geometry;
use Exception;

class InvalidFileException extends Exception
{

    public function __construct($path)
    {
        $this->message = "Invalid file: $path";
    }
}