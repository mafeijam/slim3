<?php

namespace App\Middleware;

use App\Auth\Auth;

class Guard
{
   protected $auth;
   protected $to;

   public function __construct(Auth $auth, $to = '/login')
   {
      $this->auth = $auth;
      $this->to = $to;
   }

   public function __invoke($req, $res, $next)
   {
      if ($this->auth->check()) {
         return $next($req, $res);
      }

      flash('intend', $req->getUri()->getPath());
      flash('pjaxFullReload', true);

      return $res->withRedirect($this->to);
   }
}