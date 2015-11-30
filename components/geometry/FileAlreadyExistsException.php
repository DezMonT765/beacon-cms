<?php
namespace app\components\geometry;
use Exception;

class FileAlreadyExistsException extends Exception
{
    public function __construct($path)
    {
        $this->message = "File $path is already exists!";
    }
}