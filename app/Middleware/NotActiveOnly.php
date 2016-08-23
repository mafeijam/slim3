<?php

namespace App\Middleware;

use App\Auth\Guard;

class NotActiveOnly
{
   protected $auth;

   public function __construct(Guard $auth)
   {
      $this->auth = $auth;
   }

   public function __invoke($req, $res, $next)
   {
      if ($this->auth->check() == false || $this->auth->user()->active == 1) {
         return $res->withRedirect('/');
      }

      return $next($req, $res);
   }
}