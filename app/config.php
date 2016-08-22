<?php

return [
   'settings' => [
      'displayErrorDetails' => true
   ],

   'jwt' => [
      'key' => env('JWT_KEY'),
      'exp' => env('JWT_EXP', 3600)
   ],

   'database' => [
      'host'      => env('DB_HOST', 'localhost'),
      'dbname'    => env('DB_NAME', 'test'),
      'username'  => env('DB_USER', 'root'),
      'password'  => env('DB_PASS', ''),
      'fetchMode' => PDO::FETCH_OBJ
   ],

   'mailtrap' => [
      'username' => env('MAIL_USERNAME'),
      'password' => env('MAIL_PASSWORD')
   ]
];