<?php

namespace App\Middleware;

class Back
{
   public function __invoke($req, $res, $next)
   {
      $resp = $next($req, $res);
      flash('back_uri', $req->getUri()->getPath());
      return $resp;
   }
}