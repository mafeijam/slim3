<?php

session_start();

require '../vendor/autoload.php';
require 'helper.php';

use App\App;
use App\Query;
use App\Auth\Auth;
use App\Middleware\JwtCheck;
use Dotenv\Dotenv;
use Carbon\Carbon;
use Whoops\Run as Whoops;
use Whoops\Handler\PrettyPageHandler;

(new Dotenv(dirname(__DIR__)))->load();
Carbon::setLocale('zh-TW');

$config = require 'config.php';

if ($config['useCustomResolver']) {
   $customResolver = require 'custom-resolver.php';
   $config = array_merge($config, $customResolver);
}

$app = new App($config);

$container = $app->getContainer();

if ($config['debug']) {
   unset($container['errorHandler']);
   unset($container['phpErrorHandler']);

   $whoops = new Whoops;
   $whoops->pushHandler(new PrettyPageHandler);
   $whoops->register();
}

foreach (glob(__DIR__ . '/setup-*.php') as $setup) {
   require $setup;
}

$container['auth'] = function($c) {
   return new Auth($c['jwt']);
};

$container['query'] = function($c) {
   return new Query;
};

require 'global-middleware.php';

$app->group('/api', function() use ($app){
   require '../routes/api.php';
})->add(new JwtCheck($config['jwt']['key'], ['/api/users']));

require '../routes/web.php';
