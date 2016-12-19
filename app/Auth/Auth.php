<?php

namespace App\Auth;

use Exception;
use Firebase\JWT\JWT;

class Auth
{
   protected $jwt;
   protected $authUser = null;
   protected $userProfile;

   public function __construct($jwt, array $alg = ['HS256'])
   {
      $this->jwt = $jwt;

      if (isset($_COOKIE['token'])) {
         try {
            $this->authUser = JWT::decode($_COOKIE['token'], $jwt['key'], $alg)->user;
            $this->userProfile = db('select * from users where id = ?', [$this->authUser->id])->fetch();
         } catch (Exception $e) {
            $this->authUser = null;
            setcookie('token', '', time() - 1);
         }
      }
   }

   public function login($username, $password, $remember)
   {
      extract($this->jwt);
      $user = db('select id, username, password, last_login from users where username = ?', [$username])->fetch();
      if ($user && password_verify($password, $user->password)) {
         db('update users set last_login = NOW(), last_login_display = ? where id = ?', [$user->last_login, $user->id]);

         $time = isset($remember) ? strtotime('+1 year') : strtotime($exp);

         $token = [
            'user' => [
               'id' => $user->id,
               'name' => $user->username
            ],
            'exp' => $time
         ];

         flash('success', '登入成功');
         setcookie('token', JWT::encode($token, $key), $time, '', '', false, true);
         return true;
      }

      return false;
   }

   public function logout()
   {
      setcookie('token', '', time() - 1);
      session_destroy();
   }

   public function check()
   {
      return isset($this->authUser);
   }

   public function user()
   {
      return $this->userProfile;
   }
}