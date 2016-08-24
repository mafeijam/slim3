<?php

namespace App\Middleware;

class CsrfCheck
{
   use ExpectableRouteTrait;

   protected $except = [];
   protected $key;

   public function __construct(array $except = [], $key = 'csrf_token')
   {
      $this->except = $this->parseExcept($except);
      $this->key = $key;
   }

   public function __invoke($req, $res, $next)
   {
      if ($req->isGet() || $this->isExcepted($req->getUri()->getPath())) {
         return $next($this->withToken($req), $res);
      }

      return $this->check($req->getParam($this->key)) ?
         $next($this->withToken($req), $res) :
         $this->abort($res);
   }

   protected function check($token)
   {
      if (!isset($_SESSION[$this->key])) {
         return false;
      }

      $stored = $_SESSION[$this->key];
      unset($_SESSION[$this->key]);
      return $stored === $token;
   }

   protected function withToken($req)
   {
      if (!isset($_SESSION[$this->key])) {
         $_SESSION[$this->key] = random_str(32);
      }

      return $req->withAttribute($this->key, $_SESSION[$this->key]);
   }

   protected function abort($res)
   {
      flash('errors', ['message' => '操作失敗']);
      return $res->withRedirect('/');
   }
}