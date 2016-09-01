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

      $mostActiveUsers = db('select username,
         (select count(*) from shares where shares.user_id = users.id) as shares
         from users
         having shares > 0
         order by shares desc
         limit 5')->fetchAll();

      return view($res, 'home', compact('shares', 'hots', 'mostActiveUsers', 'pages', 'page', 'prev', 'next'));
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

      $comments = db('select comments.*,
         users.username, users.email
         from comments
         inner join users
         on comments.user_id = users.id
         where comments.share_id = ?
         order by comments.created_at desc', [$share->id])->fetchAll();

      return view($res, 'share.single', compact('share', 'others', 'likedUsers', 'comments'));
   }

   public function create($req, $res)
   {
      $categories = db('select * from categories');

      return view($res, 'share.create', compact('categories'));
   }

   public function save($req, $res)
   {
      q()->saveShare($req);
      return $res->withRedirect('/');
   }

   public function toggleLike($req, $res, $args)
   {
      q()->toggleLike($args['id']);

      return $res->withJson(['id' => auth('id'), 'username' => auth('username'), 'email' => auth('email')]);
   }

   public function comments($req, $res)
   {
      extract($req->getParams());

      db('insert into comments set user_id = ?, share_id = ?, body = ?', [auth('id'), $share_id, $comment]);

      return back($res);
   }
}