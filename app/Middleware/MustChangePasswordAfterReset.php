<?php

namespace App\Middleware;

use App\Auth\Guard;

class MustChangePasswordAfterReset
{
   protected $auth;
   protected $allowedPath = [];

   public function __construct(Guard $auth, $allowedPath = ['/changepassword', '/logout'])
   {
      $this->auth = $auth;
      $this->allowedPath = $allowedPath;
   }

   public function __invoke($req, $res, $next)
   {
      $path = $req->getUri()->getPath();

      if ($this->auth->passwordReset() && !in_array($path, $this->allowedPath)) {
         $_SESSION['errors'] = ['reset' => '密碼已重設，請先更改新密碼'];
         return $res->withRedirect('/changepassword');
      }

      return $next($req, $res);
   }
}