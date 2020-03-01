<?php

namespace SofortCom\Exceptions;

class SofortLibException extends \Exception
{
    public $errors;

    public function __construct($message = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}