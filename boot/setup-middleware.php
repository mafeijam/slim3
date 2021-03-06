<?php

$container['guest'] = function($c) {
   return new App\Middleware\Guest($c['auth']);
};

$container['guard'] = function($c) {
   return new App\Middleware\Guard($c['auth']);
};

$container['isActive'] = function($c) {
   return new App\Middleware\IsActive($c['auth']->user()->active);
};

$container['ajaxOnly'] = function($c) {
   return new App\Middleware\AjaxOnly;
};

$container['adminOnly'] = function($c) {
   return new App\Middleware\AdminOnly;
};