<?php
namespace app\components\geometry;
use Exception;

class FileNotFoundException extends Exception
{
    const MESSAGE = 'File not found';

    public function __construct()
    {
        $this->message = self::MESSAGE;
    }
}