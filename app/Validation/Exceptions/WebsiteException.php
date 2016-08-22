<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class WebsiteException extends ValidationException
{
   public static $defaultTemplates = [
       self::MODE_DEFAULT => [
           self::STANDARD => '{{name}} 不是有效的網址',
       ]
   ];
}
