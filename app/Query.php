<?php

namespace App;

class Query
{
   public function getAllSharesWithLikesCount($start, $perPage)
   {
      return db('select shares.*,
                  users.username, users.email,
                  categories.name as cat_name,
                  (select count(*) from share_like where share_like.share_id = shares.id) as likes,
                  (select count(*) from comments where comments.share_id = shares.id) as comments
                  from shares
                  inner join users
                  on users.id = shares.user_id
                  inner join categories
                  on categories.id = shares.cat_id
                  order by shares.created_at desc
                  limit ?, ?', [$start, $perPage])->fetchAll();
   }

   public function getHotShares($limit = 5)
   {
      return db('select * from shares order by views desc limit ' . $limit)->fetchAll();
   }

   public function getTotalShares()
   {
      return db('select count(id) as total from shares')->fetch()->total;
   }

   public function getShareByIdWithIikedUsers($id)
   {
      return db('select shares.*,
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
   }

   public function shareViewsUp($id)
   {
       db('update shares set views = views + 1 where id = ?', [$id]);
   }

   public function getOtherShares($share)
   {
      return db('select * from shares where user_id = ? and id != ? order by created_at desc limit 3',
                  [$share->user_id, $share->id])->fetchAll();
   }

   public function getLikedUsers($share)
   {
      return db('select username, email, id from users where id in ('.$share->liked_users.')')->fetchAll();
   }

   public function saveShare($req)
   {
      extract($req->getParams());
      db('insert into shares set user_id = ?, cat_id = ?, title = ?, body = ?',
         [auth('id'), $category, $title, $body]);
   }

   public function toggleLike($shareId)
   {
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
   }
}