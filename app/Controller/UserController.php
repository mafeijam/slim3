<?php

namespace App\Controller;

class UserController
{
   public function index($req, $res)
   {
      $recents = db('select shares.*,
                     categories.name as cat_name,
                     count(share_like.share_id) as likes
                     from shares
                     inner join categories
                     on categories.id = shares.cat_id
                     left join share_like
                     on share_like.share_id = shares.id
                     where shares.user_id = ?
                     group by shares.id
                     order by created_at desc limit 3', [auth('id')])->fetchAll();

      $hasLikedShares = db('select group_concat(share_id) as liked_shares from share_like where user_id = ?',
                           [auth('id')])->fetch()->liked_shares;
      $likes = [];
      if ($hasLikedShares) {
         $likes = db('select * from shares where id in ('.$hasLikedShares.') and user_id != ?', [auth('id')])->fetchAll();
      }

      return view($res, 'profile', compact('recents', 'likes'));
   }

   public function create($req, $res)
   {
      extract($req->getParams());
      $password = password_hash($password, PASSWORD_DEFAULT);
      $code = random_str(60);

      db('insert into users set username = ?, password = ?, email = ?, active_code = ?',
         [$username, $password, $email, $code]);

      mailer($email, $username, 'welcome', ['code' => $code]);

      flash('success', '註冊成功，請到你的郵箱查收驗證郵件');
      flash('old', ['username' => $username]);

      return $res->withRedirect('login');
   }

   public function update($req, $res)
   {
      $inputs = $req->getParams();
      $id = auth('id');
      array_walk($inputs, function(&$v){
         $v = trim($v) ? trim($v) : null;
      });
      extract($inputs);
      add_http_prefix($website);

      $sql = 'update users set nickname = ?, website = ?, come_from = ?, email = ?, description = ?, github = ?';
      $values = [$nickname, $website, $come_from, $email, $description, $github];

      if (auth('email') !== $email) {
         $code = random_str(60);
         $sql .= ', active = ?, active_code = ? where id = ?';
         array_push($values, 0, $code, $id);
         db($sql, $values);
         flash('success', '資料更新成功，請重新驗證新郵箱');
         mailer($email, auth('username'), 'welcome', ['code' => $code]);
         return $res->withRedirect('update-profile');
      }

      array_push($values, $id);
      $sql .= ' where id = ?';
      db($sql, $values);
      flash('success', '資料更新成功');
      return $res->withRedirect('update-profile');
   }
}