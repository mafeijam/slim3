<?php

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

   foreach (glob(__DIR__ . '/twig-*.php') as $twig) {
      $type = substr(basename($twig, '.php'), 5);
      $method = 'add'.ucfirst($type);
      $data = require $twig;
      switch ($type) {
         case 'global':
            foreach ($data as $k => $d) {
               $env->$method($k, $d);
            }
            break;

         default:
            foreach ($data as $d) {
               $env->$method($d);
            }
            break;
      }
   }

   $bg = [
      '/bg/geometry2.png',
      '/bg/photography.png',
      '/bg/skulls.png'
   ];
   shuffle($bg);
   $env->addGlobal('bg', $bg[0]);

   return $view;
};