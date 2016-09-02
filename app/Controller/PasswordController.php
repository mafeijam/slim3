<?php

namespace App\Controller;

class PasswordController
{
   public function index($req, $res)
   {
      return view($res, 'password.change');
   }

   public function update($req, $res)
   {
      extract($req->getParams());
      $id = auth('id');
      $user = db('select * from users where id = ?', [$id])->fetch();
      if ($user && password_verify($password, $user->password)) {
         $new = password_hash($new_password, PASSWORD_DEFAULT);
         db('update users set password = ? where id = ?', [$new, $id]);
         flash('success', '密碼更改成功');
         return $res->withRedirect('profile');
      }

      flash('errors', ['bottom' => '舊密碼錯誤']);

      return $res->withRedirect('change-password');
   }

   public function getForget($req, $res)
   {
      return view($res, 'password.forget');
   }

   public function postForget($req, $res)
   {
      $email = $req->getParam('email');
      $user = db('select * from users where email = ?', [$email])->fetch();
      $token = random_str(60);

      mailer($email, $user->username, 'reset', ['token' => $token]);

      db('update users set reset_token = ?, reset_exp = DATE_ADD(NOW(), INTERVAL 1 HOUR) where id = ?',
         [$token, $user->id]);

      flash('success', '重設密碼連結已發送');
      return $res->withRedirect('forget');
   }

   public function getReset($req, $res, $args)
   {
      $token = $args['token'];
      $query = db('select reset_token, reset_exp from users where reset_token = ?', [$token]);

      if ($query->rowCount() && strtotime($query->fetch()->reset_exp) > time()) {
         return view($res, 'password.reset', ['reset_token' => $token]);
      }

      return view($res, 'password.reset-error');
   }

   public function postReset($req, $res)
   {
      extract($req->getParams());
      $user = db('select id from users where reset_token = ?', [$reset_token])->fetch();

      if ($user) {
         $password = password_hash($password, PASSWORD_DEFAULT);
         db('update users set password = ?, reset_token = null, reset_exp = null where id = ?',
            [$password, $user->id]);
         flash('success', '密碼更改成功');
         return $res->withRedirect('login');
      }

      flash('errors', ['message' => '操作失敗']);
      return $res->withRedirect('/');
   }
}