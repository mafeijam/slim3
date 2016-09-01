<?php

namespace App\Middleware;

class AjaxOnly
{
   public function __invoke($req, $res, $next)
   {
      if ($req->isXhr()) {
         return $next($req, $res);
      }

      return $res->withRedirect('/');
   }
}