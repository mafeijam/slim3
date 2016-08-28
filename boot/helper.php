<?php

function app($key = null) {
   $app = App\App::getInstance();
   return $key ? $app[$key] : $app;
}

function db($query = null, $args = []) {
   if ($query) {
      $statement = app('db')->prepare($query);
      $statement->execute($args);
      return $statement;
   }

   return app('db');
}

function view($res, $file, $data = []) {
   return app('view')->render($res, str_replace('.', DIRECTORY_SEPARATOR, $file).'.twig', $data);
}

function mailer($to = null, $name = null, $body = null, array $data = []) {
   return $to ? app('mailer')->to($to, $name)->send($body, $data) : app('mailer');
}

function auth($key = null) {
   return $key ? app('auth')->user()->$key : app('auth');
}

function is_me($id) {
   if (auth()->check()) {
      return auth('id') === $id;
   }

   return false;
}

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

function random_str($len) {
   $factory = new RandomLib\Factory;
   $generator = $factory->getLowStrengthGenerator();
   return $generator->generateString($len, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-');
}

function add_http_prefix(&$url) {
   if ($url !== null && preg_match('#^https?://#i', $url) == false) {
      $url = 'http://' . $url;
   }
}

function flash($key, $value) {
   $_SESSION[$key] = $value;
}

function is_page_refresh() {
   return isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
}
