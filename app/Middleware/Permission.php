<?php

namespace App\Middleware;

class Permission
{
   protected $auth;

   public function __construct(\App\Auth\Guard $auth)
   {
      $this->auth = $auth;
   }

   public function __invoke($req, $res, $next)
   {
      if ($this->auth->user()->active == 0) {
         return $res->withRedirect('/need-active');
      }

      return $next($req, $res);
   }
}