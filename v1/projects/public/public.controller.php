<?php

use Firebase\JWT\JWT;
use Tuupola\Base62;

class PublicController
{
    public static function checkTicketFields($payload)
    {
        $required = array('name', 'phone');

        return FunctionsHelper::checkFields($payload, $required);
    }

}