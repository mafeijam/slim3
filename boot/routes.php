<?php

use App\Middleware\JwtCheck;

$app->group('/api', function() use ($app){
   require __DIR__ . '/../routes/api.php';
})->add(new JwtCheck($config['jwt']['key'], ['/api/users']));

require __DIR__ . '/../routes/web.php';