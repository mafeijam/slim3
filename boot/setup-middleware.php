<?php

$container['guest'] = function($c) {
   return new App\Middleware\Guest($c['auth']);
};

$container['guard'] = function($c) {
   return new App\Middleware\Guard($c['auth']);
};

$container['permission'] = function($c) {
   return new App\Middleware\Permission($c['view'], $c['auth']->user()->active);
};