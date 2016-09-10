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

   $twigGlobals = require 'twig-global.php';
   foreach ($twigGlobals as $key => $global) {
      $env->addGlobal($key, $global);
   }

   $twigFunctions = require 'twig-function.php';
   foreach ($twigFunctions as $function) {
      $env->addFunction($function);
   }

   $twigFilters = require 'twig-filter.php';
   foreach ($twigFilters as $filter) {
      $env->addFilter($filter);
   }

   return $view;
};