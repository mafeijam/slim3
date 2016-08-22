<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class Website extends AbstractRule
{
   public function validate($input)
   {
      addHttpPrefix($input);

      return filter_var($input, FILTER_VALIDATE_URL);
   }
}