<?php

namespace App\Controller;

class ShareController
{
   public function index($req, $res)
   {
      $total = q()->getTotalShares();
      $perPage = 20;
      $page = (int) $req->getQueryParam('p', 1);
      $pages = ceil($total/$perPage);
      $start = ($page - 1) * $perPage;

      if ($page < 1 || $total > 0 && $page > $pages) {
         return $res->withRedirect('/');
      }

      list($prev, $next) = prev_next($page, $pages);

      $shares = q()->getAllShares($start, $perPage);

      $hots = q()->getHotShares();

      $mostActiveUsers = q()->getMostActiveUsers();

      return view($res, 'home', compact('shares', 'hots', 'mostActiveUsers', 'pages', 'page', 'prev', 'next'));
   }

   public function show($req, $res, $args)
   {
      $id = $args['id'];

      $share = q()->getShareById($id);

      if (!$share) {
         return view($res->withStatus(404), 'share.not-found');
      }

      if (is_page_refresh() == false && is_me($share->user_id) == false) {
         q()->shareViewsUp($id);
      }

      $others = q()->getOtherShares($share);

      $likedUsers = q()->getLikedUsers($id);

      $comments = q()->getComments($id);

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
      q()->saveComment($req);
      return back($res);
   }
}