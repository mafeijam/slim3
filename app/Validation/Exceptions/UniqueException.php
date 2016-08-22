<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class UniqueException extends ValidationException
{
   public static $defaultTemplates = [
       self::MODE_DEFAULT => [
           self::STANDARD => '{{name}} 已被使用',
       ]
   ];
}
