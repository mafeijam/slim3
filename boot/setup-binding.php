<?php

use App\Cache;
use App\Query;
use App\Auth\Auth;

$container['auth'] = function($c) {
   return new Auth($c['jwt']);
};

$container['query'] = function($c) {
   return new Query;
};

$container['cache'] = function($c) {
   return new Cache($c['redis']);
};

$container['redis'] = function($c) {
   $r = new Redis;
   $r->connect(env('REDIS_HOST'), env('REDIS_PORT'));
   return $r;
};