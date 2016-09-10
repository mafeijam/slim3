<?php

namespace App\Middleware;

class AdminOnly
{
   public function __invoke($req, $res, $next)
   {
      if (auth('role') == 'admin') {
         return $next($req, $res);
      }

      return $res->withRedirect('/');
   }
}