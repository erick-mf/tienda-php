<?php

namespace App\Lib;

class ResponseHttp
{
    public static function setHeader()
    {
        header('Access-Control-Expose-Headers: Authorization');
    }
}
