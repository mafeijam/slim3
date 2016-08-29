<?php

namespace App\Middleware;

use App\Auth\Auth;

class Guest
{
   protected $auth;

   public function __construct(Auth $auth)
   {
      $this->auth = $auth;
   }

   public function __invoke($req, $res, $next)
   {
      if ($this->auth->check()) {
         return $res->withRedirect('/');
      }

      return $next($req, $res);
   }
}