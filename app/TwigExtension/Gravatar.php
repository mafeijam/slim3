<?php

namespace App\TwigExtension;

use App\Auth\Guard;

class Gravatar extends \Twig_Extension
{
   protected $email;

   public function __construct(Guard $auth)
   {
      $this->email = $auth->check() ? $auth->user()->email : null;
   }

   public function getName()
   {
      return 'gravatar';
   }

   public function getFunctions()
   {
      return [
         new \Twig_SimpleFunction('gravatar', [$this, 'gravatar'])
      ];
   }

   public function gravatar($size = 100, $default = 'mm')
   {
      $url = 'https://www.gravatar.com/avatar/';
      $url .= md5($this->email);
      $url .= '?s='.$size.'&d='.$default;
      return '<img class="gravatar" src="' . $url . '">';
   }
}