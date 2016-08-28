<?php

use Carbon\Carbon;
use Aptoma\Twig\Extension\MarkdownEngine;
use Aptoma\Twig\Extension\MarkdownExtension;

$container['view'] = function($c) {
   $view = new Slim\Views\Twig(__DIR__ . '/../view');

   $view->addExtension(new Slim\Views\TwigExtension(
       $c['router'],
       $c['request']->getUri()
   ));

   $engine = new MarkdownEngine\MichelfMarkdownEngine;
   $view->addExtension(new MarkdownExtension($engine));
   $view->addExtension(new App\TwigExtension\Gravatar($c['auth']));

   $env = $view->getEnvironment();

   $env->addGlobal('auth', [
      'check' => $c['auth']->check(),
      'user' => $c['auth']->user()
   ]);

   $desktop = preg_match('/windows|win32/i', $_SERVER['HTTP_USER_AGENT']) ? true : false;

   $env->addGlobal('isDesktop', $desktop);

   if ($c['auth']->check()) {
      $shared = new Twig_SimpleFunction('shared', function(){
         return db('select count(id) as count from shares where user_id = ?', [auth('id')])->fetch()->count;
      });
      $env->addFunction($shared);
   }

   $diffForHumans = new Twig_SimpleFilter('diffForHumans', function ($string) {
      return Carbon::createFromTimestamp(strtotime($string))->diffForHumans();
   });

   $env->addFilter($diffForHumans);

   $isLiked = new Twig_SimpleFunction('isLiked', function($id, $shareId) {
      return db('select * from share_like where user_id = ? and share_id = ?', [$id, $shareId])->rowCount();
   });

   $env->addFunction($isLiked);

   return $view;
};