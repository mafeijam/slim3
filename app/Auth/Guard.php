<?php

namespace App\Auth;

use Exception;
use Firebase\JWT\JWT;
use Carbon\Carbon;

class Guard
{
   protected $authUser = null;

   public function __construct($db, $key, array $alg = ['HS256'])
   {
      Carbon::setLocale('zh-TW');

      if (isset($_COOKIE['token'])) {
         try {
            $this->authUser = JWT::decode($_COOKIE['token'], $key, $alg)->user;
            $query = $db->prepare('select * from users where id = ?');
            $query->execute([$this->authUser->id]);
            $user = $query->fetch();
            $this->authUser->email = $user->email;
            $_SESSION['email'] = $user->email;
            $this->authUser->nickname = $user->nickname;
            $this->authUser->come_from = $user->come_from;
            $this->authUser->website = $user->website;
            $this->authUser->description = $user->description;
            $this->authUser->joined_ago = $this->diffForHumans($user->joined_at);
            $this->authUser->reset = $user->reset;
            $this->authUser->last_login = $user->last_login;
            $this->authUser->last_ago = $this->diffForHumans($user->last_login);
            $this->authUser->active = $user->active;
         } catch (Exception $e) {
            $this->authUser = null;
         }
      }
   }

   public function check()
   {
      return $this->authUser !== null;
   }

   public function user()
   {
      return $this->authUser;
   }

   public function passwordReset()
   {
      return $this->check() && $this->authUser->reset == 1;
   }

   protected function diffForHumans($time)
   {
      return Carbon::createFromTimestamp(strtotime($time))->diffForHumans();
   }
}