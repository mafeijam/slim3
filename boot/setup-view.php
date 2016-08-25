<?php

use Carbon\Carbon;
use Aptoma\Twig\Extension\MarkdownEngine;
use Aptoma\Twig\Extension\MarkdownExtension;

$container['view'] = function($c) {
   $view = new Slim\Views\Twig(__DIR__ . '/../view');
   $engine = new MarkdownEngine\MichelfMarkdownEngine;

   $view->addExtension(new MarkdownExtension($engine));

   $view->addExtension(new Slim\Views\TwigExtension(
       $c['router'],
       $c['request']->getUri()
   ));

   $view->addExtension(new App\TwigExtension\Gravatar($c['auth']));

   $env = $view->getEnvironment();

   $env->addGlobal('auth', [
      'check' => $c['auth']->check(),
      'user' => $c['auth']->user()
   ]);

   $desktop = preg_match('/windows|win32/i', $_SERVER['HTTP_USER_AGENT']) ? true : false;

   $env->addGlobal('isDesktop', $desktop);

   $diffForHumans = new Twig_SimpleFilter('diffForHumans', function ($string) {
      return Carbon::createFromTimestamp(strtotime($string))->diffForHumans();
   });

   $env->addFilter($diffForHumans);

   return $view;
};