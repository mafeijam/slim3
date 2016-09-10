<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

use App\App;
use Dotenv\Dotenv;
use Carbon\Carbon;
use Whoops\Run as Whoops;
use Whoops\Handler\PrettyPageHandler;

if (file_exists(__DIR__ . '/../.env')) {
   (new Dotenv(dirname(__DIR__)))->load();
}

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

require 'global-middleware.php';

require 'routes.php';
