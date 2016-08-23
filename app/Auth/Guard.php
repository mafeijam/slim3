<?php

namespace App\Auth;

use PDO;
use Exception;
use Firebase\JWT\JWT;
use Carbon\Carbon;

class Guard
{
   protected $db;
   protected $authUser = null;
   protected $userProfile;

   public function __construct(PDO $db, $key, array $alg = ['HS256'])
   {
      $this->db = $db;

      if (isset($_COOKIE['token'])) {
         try {
            $this->authUser = JWT::decode($_COOKIE['token'], $key, $alg)->user;
         } catch (Exception $e) {
            $this->authUser = null;
         }
      }
   }

   public function check()
   {
      return isset($this->authUser);
   }

   public function user()
   {
      if ($this->check()) {

         if (isset($this->userProfile)) {
            return $this->userProfile;
         }

         $query = $this->db->prepare('select * from users where id = ?');
         $query->execute([$this->authUser->id]);
         $user = $query->fetch();
         $this->userProfile = $user;
         $this->userProfile->joined_ago = $this->diffForHumans($user->joined_at);
         $this->userProfile->last_ago = $this->diffForHumans($user->last_login);

         return $this->userProfile;
      }

      return null;
   }

   public function passwordReset()
   {
      return $this->check() && $this->userProfile->reset == 1;
   }

   protected function diffForHumans($time)
   {
      return Carbon::createFromTimestamp(strtotime($time))->diffForHumans();
   }
}