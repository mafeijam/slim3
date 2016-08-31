<?php

namespace App\Middleware;

class Back
{
   public function __invoke($req, $res, $next)
   {
      if ($req->isXhr()) {
         return $next($req, $res);
      }

      $resp = $next($req, $res);
      flash('back_uri', $req->getUri()->getPath());
      return $resp;
   }
}