<?php

use Carbon\Carbon;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Aptoma\Twig\Extension\MarkdownExtension;
use Aptoma\Twig\Extension\MarkdownEngine\MichelfMarkdownEngine;

$container['view'] = function($c) {
   $view = new Twig(__DIR__ . '/../view');

   $view->addExtension(new TwigExtension(
       $c['router'],
       $c['request']->getUri()
   ));

   $view->addExtension(new MarkdownExtension(new MichelfMarkdownEngine));

   $env = $view->getEnvironment();

   $env->addGlobal('auth', [
      'check' => auth()->check(),
      'user' => auth()->user()
   ]);

   $twigFunctions = require 'twig-function.php';
   foreach ($twigFunctions as $function) {
      $env->addFunction($function);
   }

   $env->addFilter(new Twig_SimpleFilter('diffForHumans', function ($string) {
      return Carbon::createFromTimestamp(strtotime($string))->diffForHumans();
   }));

   return $view;
};