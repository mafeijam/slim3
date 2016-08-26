<?php

session_start();

require '../vendor/autoload.php';
require 'helper.php';

use Slim\App;
use Dotenv\Dotenv;
use Carbon\Carbon;
use Whoops\Run as Whoops;
use Whoops\Handler\PrettyPageHandler;
use App\Middleware\JwtCheck;

(new Dotenv(dirname(__DIR__)))->load();
Carbon::setLocale('zh-TW');

$config = require 'config.php';

$app = new App($config);

$container = $app->getContainer();

if ($config['debug']) {
   unset($container['errorHandler']);
   unset($container['phpErrorHandler']);

   $whoops = new Whoops;
   $whoops->pushHandler(new PrettyPageHandler);
   $whoops->register();
}

require 'services.php';

require 'global-middleware.php';

$app->group('/api', function() use ($app){
   require '../routes/api.php';
})->add(new JwtCheck($config['jwt']['key'], ['/api/users']));

require '../routes/web.php';
