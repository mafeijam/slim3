<?php

$skip = [];

foreach (glob(__DIR__ . '/../app/Controller/*.php') as $controller) {
   $key = basename($controller, '.php');

   if (in_array($key, $skip)) {
      continue;
   }

   $class = 'App\Controller\\'.$key;
   $container[$key] = function($c) use ($class){
      return new $class;
   };
}
