<?php

namespace App\Middleware;

class IsActive
{
   protected $isActive;

   public function __construct($isActive)
   {
      $this->isActive = $isActive;
   }

   public function __invoke($req, $res, $next)
   {
      return $this->canShare() ?
         $next($req, $res) :
         view($res, 'need-active');
   }

   protected function canShare()
   {
      return $this->isActive == 1;
   }
}