<?php

return [
   'gravatar' => new Twig_SimpleFunction('gravatar', function ($size = 100, $email = null, $default = 'mm') {
      $url = 'https://www.gravatar.com/avatar/';
      $url .= $email ? md5($email) : md5(auth('email'));
      $url .= '?s='.$size.'&d='.$default;
      return '<img class="gravatar" src="' . $url . '">';
   }),

   'isLiked' => new Twig_SimpleFunction('isLiked', function($id, $shareId) {
      return db('select * from share_like where user_id = ? and share_id = ?', [$id, $shareId])->rowCount();
   }),

   'shared' => new Twig_SimpleFunction('shared', function(){
      $id = auth()->check() ? auth('id') : null;
      return db('select count(id) as count from shares where user_id = ?', [$id])->fetch()->count;
   }),

   'commented' => new Twig_SimpleFunction('commented', function(){
      $id = auth()->check() ? auth('id') : null;
      return db('select count(id) as count from comments where user_id = ?', [$id])->fetch()->count;
   }),

   'isDesktop' => new Twig_SimpleFunction('isDesktop', function() {
      return preg_match('/windows|win32/i', $_SERVER['HTTP_USER_AGENT']) ? true : false;
   }),
];