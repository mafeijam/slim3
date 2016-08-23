<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class AvailableException extends ValidationException
{
   public static $defaultTemplates = [
       self::MODE_DEFAULT => [
           self::STANDARD => '{{name}} 尚未有用戶註冊',
       ]
   ];
}
