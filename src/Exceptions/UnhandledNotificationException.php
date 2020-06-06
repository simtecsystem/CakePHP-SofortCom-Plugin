<?php

namespace SofortCom\Exceptions;

class UnhandledNotificationException extends SofortLibException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}