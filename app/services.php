<?php

$container['db'] = function($c) {
   extract($c->get('database'));
   $pdo = new PDO('mysql:host='.$host.';dbname='.$dbname, $username, $password);
   $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $fetchMode);
   $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   return $pdo;
};

$container['view'] = function($c) {
   $view = new Slim\Views\Twig(__DIR__ . '/../view');

   $view->addExtension(new Slim\Views\TwigExtension(
       $c['router'],
       $c['request']->getUri()
   ));

   $view->addExtension(new App\TwigExtension\Gravatar($c['auth']));

   $view->getEnvironment()->addGlobal('auth', [
      'check' => $c['auth']->check(),
      'user' => $c['auth']->user()
   ]);

   $desktop = preg_match('/windows|win32/i', $_SERVER['HTTP_USER_AGENT']) ? true : false;

   $view->getEnvironment()->addGlobal('isDesktop', $desktop);

   return $view;
};

$container['auth'] = function($c) {
   return new App\Auth\Guard($c['db'], $c['jwt']['key']);
};

$container['guest'] = function($c) {
   return new App\Middleware\Guest($c['auth']);
};

$container['guard'] = function($c) {
   return new App\Middleware\Guard($c['auth']);
};

$container['AuthController'] = function($c) {
   return new App\Controller\AuthController($c['db'], $c['view'], $c['mailer'], $c['auth'], $c['jwt']);
};

$container['PageController'] = function($c) {
   return new App\Controller\PageController($c['db'], $c['view']);
};

require 'Mail/setup.php';

require 'Validation/setup.php';