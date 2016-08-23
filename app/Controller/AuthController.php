<?php

namespace App\Controller;

use PDO;
use App\Mail\Mailer;
use App\Auth\Guard;
use Firebase\JWT\JWT;

class AuthController
{
   protected $db;
   protected $jwt;
   protected $mailer;
   protected $auth;

   public function __construct(PDO $db, Mailer $mailer, Guard $auth, $jwt)
   {
      $this->db = $db;
      $this->jwt = $jwt;
      $this->mailer = $mailer;
      $this->auth = $auth;
   }

   public function login($req, $res)
   {
      extract($this->jwt);

      extract($req->getParams());
      $query = $this->db->prepare('select * from users where username = ?');
      $query->execute([$username]);
      $user = $query->fetch();
      $time = time() + $exp;

      if ($user && password_verify($password, $user->password)) {
         $this->db->prepare('update users set last_login = NOW() where id = ?')->execute([$user->id]);

         if ($req->getParam('remember') !== null) {
            $time = time() + 3600*24*365;
         }

         $token = [
            'user' => [
               'id' => $user->id,
               'name' => $user->username
            ],
            'exp' => $time
         ];

         flash('success', '登入成功');
         setcookie('token', JWT::encode($token, $key), $time, '', '', false, true);
         return $res->withRedirect('/profile');
      }

      flash('errors', ['bottom' => '使用者名稱或密碼錯誤']);
      flash('old', $req->getParams());

      return $res->withRedirect('/login');
   }

   public function logout($req, $res)
   {
      setcookie('token', '', time() - 1);
      session_destroy();
      return $res->withRedirect('/');
   }

   public function register($req, $res)
   {
      extract($req->getParams());
      $password = password_hash($password, PASSWORD_DEFAULT);
      $code = random_str(32);

      $this->db->prepare('insert into users set username = ?, password = ?, email = ?, active_code = ?')
           ->execute([$username, $password, $email, $code]);

      flash('success', '註冊成功，請到你的郵箱查收驗證郵件');
      $this->mailer->to($email, $username)->send('welcome', ['code' => $code]);

      return $res->withRedirect('/login');
   }

   public function active($req, $res, $args)
   {
      $query = $this->db->prepare('select * from users where active_code = ?');
      $query->execute([$args['code']]);

      if ($user = $query->fetch()) {
         $this->db->prepare('update users set active = ?, active_code = ? where id = ?')
              ->execute([1, null, $user->id]);
         flash('success', '電郵驗證成功');
         flash('old', ['username' => $user->username]);
         $to = $this->auth->check() ? '/profile' : '/login';
         return $res->withRedirect($to);
      }

      flash('errors', ['message' => '驗證碼錯誤']);
      return $res->withRedirect('/');
   }

   public function reactive($req, $res)
   {
      $code = random_str(32);
      $this->db->prepare('update users set active_code = ? where id = ?')
           ->execute([$code, $this->user()->id]);
      $this->mailer->to($this->user()->email, $this->user()->username)->send('welcome', ['code' => $code]);
      flash('success', '驗證成功發送');
      return $res->withRedirect('/profile');
   }

   public function forget($req, $res)
   {
      $email = $req->getParam('email');
      $query = $this->db->prepare('select * from users where email = ?');
      $query->execute([$email]);
      $newPassword = random_str(8);
      $hash = password_hash($newPassword, PASSWORD_DEFAULT);
      $user = $query->fetch();

      $this->mailer->to($email, $user->username)->send('reset', ['password' => $newPassword]);

      $this->db->prepare('update users set password = ?, reset = ? where id = ?')->execute([$hash, 1, $user->id]);

      flash('success', '新密碼已發送到你的郵箱');
      return $res->withRedirect('/login');
   }

   public function changePassword($req, $res)
   {
      extract($req->getParams());
      $id = $this->user()->id;
      $query = $this->db->prepare('select * from users where id = ?');
      $query->execute([$id]);
      $user = $query->fetch();
      if (password_verify($password, $user->password)) {
         $new = password_hash($new_password, PASSWORD_DEFAULT);
         $this->db->prepare('update users set password = ?, reset = ? where id = ?')->execute([$new, 0, $id]);
         flash('success', '密碼更改成功');
         return $res->withRedirect('/profile');
      }

      flash('errors', ['message' => '舊密碼錯誤']);

      return $res->withRedirect('/changepassword');
   }

   public function update($req, $res)
   {
      $inputs = $req->getParams();
      $id = $this->user()->id;
      array_walk($inputs, function(&$v){
         $v = trim($v);
      });
      extract($inputs);
      addHttpPrefix($website);

      $sql = 'update users set nickname = ?, website = ?, come_from = ?, email = ?, description = ?';

      $values = [$nickname, $website, $come_from, $email, $description];

      if ($this->user()->email !== $email) {
         $code = random_str(32);
         $sql .= ', active = ?, active_code = ? where id = ?';
         array_push($values, 0, $code, $id);
         $this->db->prepare($sql)->execute($values);
         $_SESSION['success'] = '資料更新成功，請重新驗證新郵箱';
         $this->mailer->to($email, $this->user()->username)->send('welcome', ['code' => $code]);
         return $res->withRedirect('/update');
      }

      array_push($values, $id);
      $sql .= ' where id = ?';
      $this->db->prepare($sql)->execute($values);

      flash('success', '資料更新成功');
      return $res->withRedirect('/update');
   }

   protected function user()
   {
      return $this->auth->user();
   }
}