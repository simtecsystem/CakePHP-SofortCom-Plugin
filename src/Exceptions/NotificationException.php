<?php

namespace SofortCom\Exceptions;

class NotificationException extends SofortLibException
{
    public function __construct(\SofortLibNotification $sofortLibNotification)
    {
        $message = 'Invalid xml data.';
        if (!empty($sofortLibNotification->errors['error']['message']))
        {
            $message = $sofortLibNotification->errors['error']['message'];
            $this->errors = $sofortLibNotification->errors;
        }
        parent::__construct($message);
    }
}