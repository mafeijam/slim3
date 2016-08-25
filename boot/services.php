<?php

foreach (glob(__DIR__ . '/setup-*.php') as $setup) {
   require $setup;
}

$container['auth'] = function($c) {
   return new App\Auth\Guard($c['db'], $c['jwt']['key']);
};

$container['AuthController'] = function($c) {
   return new App\Controller\AuthController($c['db'], $c['mailer'], $c['auth'], $c['view'], $c['jwt']);
};

$container['PageController'] = function($c) {
   return new App\Controller\PageController($c['db'], $c['view']);
};
