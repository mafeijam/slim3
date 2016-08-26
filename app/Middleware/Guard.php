<?php

namespace App\Middleware;

use App\Auth\Guard;

class Guard
{
   protected $auth;
   protected $to;

   public function __construct(Guard $auth, $to = '/login')
   {
      $this->auth = $auth;
      $this->to = $to;
   }

   public function __invoke($req, $res, $next)
   {
      if ($this->auth->check()) {
         return $next($req, $res);
      }

      $path = $req->getUri()->getPath();
      $intend = $path == $this->to ? '/profile' : $path;
      $_SESSION['intend'] = $intend;

      $_SESSION['pjaxFullReload'] = true;

      return $res->withRedirect($this->to);
   }
}