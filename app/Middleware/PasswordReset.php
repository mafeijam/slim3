<?php

namespace App\Middleware;

use App\Auth\Guard;

class PasswordReset
{
   protected $auth;

   public function __construct(Guard $auth)
   {
      $this->auth = $auth;
   }

   public function __invoke($req, $res, $next)
   {
      $path = $req->getUri()->getPath();

      if ($this->auth->passwordReset()
            && $path != '/changepassword'
            && $path != '/logout'
         ) {
         $_SESSION['errors'] = ['reset' => '密碼已重設，請先更改新密碼'];
         return $res->withRedirect('/changepassword');
      }

      return $next($req, $res);
   }
}