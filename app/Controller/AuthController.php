<?php

namespace App\Controller;

class AuthController
{
   public function login($req, $res)
   {
      extract($req->getParams());

      if (auth()->login($username, $password, $req->getParam('remember'))) {
         $to = isset($_SESSION['intend']) ? $_SESSION['intend'] : '/profile';
         unset($_SESSION['intend']);
         return $res->withRedirect($to);
      }

      flash('errors', ['bottom' => '使用者名稱或密碼錯誤']);
      flash('old', $req->getParams());
      return back($res);
   }

   public function logout($req, $res)
   {
      auth()->logout();
      return $res->withRedirect('/');
   }

   public function active($req, $res, $args)
   {
      $user = db('select id, username from users where active_code = ?', [$args['code']])->fetch();

      if ($user) {
         db('update users set active = ?, active_code = ? where id = ?', [1, null, $user->id]);
         flash('success', '電郵驗證成功');
         flash('old', ['username' => $user->username]);
         $to = auth()->check() ? '/profile' : '/login';
         return $res->withRedirect($to);
      }

      return view($res, 'active-error');
   }

   public function reactive($req, $res)
   {
      $code = random_str(60);
      db('update users set active_code = ? where id = ?', [$code, auth('id')]);
      mailer(auth('email'), auth('username'), 'welcome', ['code' => $code]);
      flash('success', '驗證成功發送');
      return $res->withRedirect('profile');
   }
}