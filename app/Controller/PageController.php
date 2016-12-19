<?php

namespace App\Controller;

class PageController
{
   public function login($req, $res)
   {
      return view($res, 'login');
   }

   public function register($req, $res)
   {
      return view($res, 'register');
   }

   public function update($req, $res)
   {
      return view($res, 'update-profile');
   }

   public function about($req, $res)
   {
      return view($res, 'about');
   }

   public function members($req, $res)
   {
      $members = db('select id, username, email from users')->fetchAll();
      return view($res, 'member.index', compact('members'));
   }

   public function membersShow($req, $res, $args)
   {
      $member = db('select * from users where username = ?', [$args['name']])->fetch();

      if (!$member) {
         return view($res, 'member.error', $args);
      }

      $recents = db('select shares.*,
                     categories.name as cat_name,
                     (select count(*) from share_like where share_like.share_id = shares.id) as likes,
                     (select count(*) from comments where comments.share_id = shares.id) as comments
                     from shares
                     inner join categories
                     on categories.id = shares.cat_id
                     where shares.user_id = ?
                     order by created_at desc limit 3', [$member->id])->fetchAll();

      $likes = db('select *
         from shares
         where id in (
            select share_id from share_like where user_id = ?)
         and user_id != ?', [$member->id, $member->id])->fetchAll();

      return view($res, 'member.show', compact('member', 'recents', 'likes'));
   }
}