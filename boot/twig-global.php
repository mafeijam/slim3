<?php

return [
   'totalUsers'    => db('select count(id) as total from users')->fetch()->total,
   'totalShares'   => db('select count(id) as total from shares')->fetch()->total,
   'totalComments' => db('select count(id) as total from comments')->fetch()->total,
   'auth' => [
      'check' => auth()->check(),
      'user'  =>  auth()->user()
   ]
];