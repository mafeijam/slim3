<?php

namespace App;

class Query
{
   public function getAllSharesWithLikesCount($start, $perPage)
   {
      return db('select shares.*,
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
      return db('select email, id from users where id in ('.$share->liked_users.')')->fetchAll();
   }
}