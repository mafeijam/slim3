<?php

namespace App\Controller;

class ShareController
{
   public function index($req, $res)
   {
      $total = q()->getTotalShares();
      $perPage = 2;
      $page = (int) $req->getQueryParam('p', 1);
      $pages = ceil($total/$perPage);
      $start = ($page - 1) * $perPage;

      if ($page < 1 || $page > $pages) {
         return $res->withRedirect('/');
      }

      list($prev, $next) = prev_next($page, $pages);

      $shares = q()->getAllSharesWithLikesCount($start, $perPage);

      $hots = q()->getHotShares();

      return view($res, 'home', compact('shares', 'hots', 'pages', 'page', 'prev', 'next'));
   }

   public function show($req, $res, $args)
   {
      $id = $args['id'];

      $share = q()->getShareByIdWithIikedUsers($id);

      if ($share->id == null) {
         return view($res->withStatus(404), 'share.not-found');
      }

      if (is_page_refresh() == false && is_me($share->user_id) == false) {
         q()->shareViewsUp($id);
      }

      $others = q()->getOtherShares($share);

      $likedUsers = [];
      if ($share->liked_users) {
         $likedUsers = q()->getLikedUsers($share);
      }

      return view($res, 'share.single', compact('share', 'others', 'likedUsers'));
   }

   public function create($req, $res)
   {
      $categories = db('select * from categories');

      return view($res, 'share.create', compact('categories'));
   }

   public function save($req, $res)
   {
      extract($req->getParams());
      db('insert into shares set user_id = ?, cat_id = ?, title = ?, body = ?',
         [auth('id'), $category, $title, $body]);
      return $res->withRedirect('/');
   }

   public function toggleLike($req, $res, $args)
   {
      if (!$req->isXhr()) {
         return $res->withRedirect('/');
      }

      $shareId = $args['id'];

      $key = [auth('id'), $shareId];

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
            [auth('id'), $shareId, $shareId]);
      }

      return $res->withJson(['id' => auth('id'), 'email' => auth('email')]);
   }
}