<?php

namespace App\Middleware;

class PjaxFullReload
{
   public function __invoke($req, $res, $next)
   {
      if (isset($_SESSION['pjaxFullReload'])) {
         unset($_SESSION['pjaxFullReload']);
         $res = $res->withStatus(401);
      }

      return $next($req, $res);
   }
}