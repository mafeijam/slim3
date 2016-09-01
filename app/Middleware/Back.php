<?php

namespace App\Middleware;

class Back
{
   public function __invoke($req, $res, $next)
   {
      flash('back_uri', $req->getHeaderLine('referer'));
      return $next($req, $res);
   }
}