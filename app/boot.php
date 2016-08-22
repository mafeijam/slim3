<?php

session_start();

require '../vendor/autoload.php';
require '../app/helper.php';

use Slim\App;
use Dotenv\Dotenv;
use App\Middleware\JwtCheck;
use App\Middleware\CsrfCheck;
use App\Middleware\PjaxHeader;
use App\Middleware\TwigGlobalVar;
use App\Middleware\PasswordReset;

(new Dotenv(dirname(__DIR__)))->load();

$config = require 'config.php';

$app = new App($config);

$container = $app->getContainer();

require 'services.php';

$app->add(new TwigGlobalVar($container['view'], $container['auth']));
$app->add(new CsrfCheck(['/api/*']));
$app->add(new PjaxHeader);
$app->add(new PasswordReset($container['auth']));

$app->group('/api', function() use ($app){
   require '../routes/api.php';
})->add(new JwtCheck($config['jwt']['key'], ['/api/users']));

require '../routes/web.php';
