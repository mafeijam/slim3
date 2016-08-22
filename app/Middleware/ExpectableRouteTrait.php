<?php

namespace App\Middleware;

trait ExpectableRouteTrait
{
   protected function isExcepted($uri)
   {
      foreach ($this->except as $e) {
         if (preg_match($e, $uri)) {
            return true;
         }
      }

      return false;
   }

   protected function parseExcept(array $except)
   {
      return array_map(function($v){
         return '#'.str_replace('*', '(.*)', $v).'$#i';
      }, $except);
   }
}