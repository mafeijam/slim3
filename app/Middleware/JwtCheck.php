<?php

namespace App\Middleware;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtCheck
{
   use ExpectableRouteTrait;

   protected $key;
   protected $alg = [];
   protected $except = [];

   public function __construct($key, array $except = [], array $alg = ['HS256'])
   {
      $this->key = $key;
      $this->alg = $alg;
      $this->except = $this->parseExcept($except);
   }

   public function __invoke($req, $res, $next)
   {
      if ($this->isExcepted($req->getUri()->getPath())) {
         return $next($req, $res);
      }

      $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;

      if (is_null($token)) {
         return $this->resp($req, $res, 'Unauthorized request.');
      }

      try {
         $authUser = JWT::decode($token, $this->key, $this->alg)->user;
         return $next($this->withUser($req, $authUser), $res);
      } catch (ExpiredException $e) {
         return $this->resp($req, $res, 'Token expired.');
      } catch (Exception $e) {
         return $this->resp($req, $res, 'Unauthorized request.');
      }
   }

   protected function withUser($req, $user)
   {
      return $req->withAttribute('user', $user);
   }

   protected function resp($req, $res, $message)
   {
      return $req->isXhr() ?
         $res->withJson(['ok' => false, 'message' => $message], 401) :
         $res->withRedirect('/login');
   }
}