<?php

namespace App\Controller;

class ShareController
{
   public function index($req, $res)
   {
      $shares = db('select shares.*,
                     users.username, users.email,
                     categories.name as cat_name,
                     count(share_like.share_id) as likes
                     from shares
                     inner join users
                     on users.id = shares.user_id
                     inner join categories
                     on categories.id = shares.cat_id
                     left join share_like
                     on share_like.share_id = shares.id
                     group by shares.id
                     order by shares.created_at desc')
            ->fetchAll();

      $ids = array_column($shares, 'id');

      $hots = db('select * from shares order by views desc limit 5')->fetchAll();

      return view($res, 'home', compact('shares', 'hots'));
   }

   public function show($req, $res, $args)
   {
      $id = $args['id'];

      $share = db('select shares.*,
                  users.username, users.email, users.description, users.github,
                  categories.name as cat_name,
                  count(share_like.share_id) as likes,
                  group_concat(share_like.user_id) as liked_users
                  from shares
                  inner join users
                  on users.id = shares.user_id
                  inner join categories
                  on categories.id = shares.cat_id
                  left join share_like
                  on share_like.share_id = shares.id
                  where shares.id = ?', [$id])->fetch();

      if ($share->id == null) {
         return view($res->withStatus(404), 'share-not-found');
      }

      if (is_page_refresh() == false && is_me($share->user_id) == false) {
         db('update shares set views = views + 1 where id = ?', [$id]);
      }

      $others = db('select * from shares where user_id = ? and id != ? order by created_at desc limit 3',
                   [$share->user_id, $share->id])->fetchAll();

      $likedUsers = [];
      if ($share->liked_users) {
         $likedUsers = db('select email, id from users where id in ('.$share->liked_users.')')->fetchAll();
      }

      return view($res, 'share-single', compact('share', 'others', 'likedUsers'));
   }

   public function create($req, $res)
   {
      $categories = db('select * from categories');

      return view($res, 'share-add', compact('categories'));
   }

   public function save($req, $res)
   {
      extract($req->getParams());
      db('insert into shares set user_id = ?, cat_id = ?, title = ?, body = ?',
         [auth('id'), $category, $title, $body]);
      return $res->withRedirect('/');
   }

   public function like($req, $res, $args)
   {
      if (!$req->isXhr()) {
         return $res->withRedirect('/');
      }

      $key = [auth('id'), $args['id']];

      $isLiked = db('select * from share_like where user_id = ? and share_id = ?', $key)->fetch();

      if ($isLiked) {
         db('delete from share_like where user_id = ? and share_id = ?', $key);
      } else {
         db('insert into share_like (user_id, share_id)
               select ?, ?
               from shares
               where exists (
                  select id
                  from shares
                  where id = ?)
               limit 1',
            [auth('id'), $args['id'], $args['id']]);
      }

      return $res->withJson(['id' => auth('id'), 'email' => auth('email')]);
   }
}