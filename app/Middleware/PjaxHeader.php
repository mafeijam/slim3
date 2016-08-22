<?php

namespace App\Middleware;

class PjaxHeader
{
   public function __invoke($req, $res, $next)
   {
      $res = $res->withHeader('X-PJAX-URL', $req->getUri()->__toString());
      return $next($req, $res);
   }
}