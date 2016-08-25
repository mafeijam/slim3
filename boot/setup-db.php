<?php

$container['db'] = function($c) {
   extract($c->get('database'));
   $pdo = new PDO('mysql:host='.$host.';dbname='.$dbname, $username, $password);
   $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $fetchMode);
   $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $pdo->exec('SET NAMES utf8');
   return $pdo;
};