<?php

function dd($var) {
   dump($var);
   exit;
}

function env($key, $defualt = null) {
   $value = getenv($key);
   return $value === false ? $defualt : $value;
}

function array_get(array $array, array $keys) {
   return array_intersect_key($array, array_flip($keys));
}

function auth_user($req) {
   return $req->getAttribute('user');
}

function random_str($len) {
   $factory = new RandomLib\Factory;
   $generator = $factory->getLowStrengthGenerator();
   return $generator->generateString($len, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
}

function addHttpPrefix(&$url) {
   if ($url !== null && preg_match('#^https?://#i', $url) == false) {
      $url = 'http://' . $url;
   }
}

function flash($key, $value) {
   $_SESSION[$key] = $value;
}