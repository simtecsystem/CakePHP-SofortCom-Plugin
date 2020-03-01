<?php

namespace SofortCom\Exceptions;

class RequestException extends SofortLibException
{
    public function __construct($message = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}