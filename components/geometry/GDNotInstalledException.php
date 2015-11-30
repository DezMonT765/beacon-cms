<?php
namespace app\components\geometry;
use Exception;

class GDNotInstalledException extends Exception
{
    public function __construct()
    {
        $this->message = "The GD library is not installed";
    }
}