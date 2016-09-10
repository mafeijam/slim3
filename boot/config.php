<?php

return [
   'settings' => [
      'displayErrorDetails' => true
   ],

   'debug' => true,

   'useCustomResolver' => false,

   'jwt' => [
      'key' => env('JWT_KEY'),
      'exp' => env('JWT_EXP', '+1 day')
   ],

   'database' => [
      'host'      => env('DB_HOST', 'localhost'),
      'dbname'    => env('DB_NAME', 'test'),
      'username'  => env('DB_USER', 'root'),
      'password'  => env('DB_PASS', ''),
      'fetchMode' => PDO::FETCH_OBJ
   ],

   'mailtrap' => [
      'username' => env('MAILTRAP_USERNAME'),
      'password' => env('MAILTRAP_PASSWORD')
   ]
];