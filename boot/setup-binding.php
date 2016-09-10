<?php

use App\Query;
use App\Auth\Auth;

$container['auth'] = function($c) {
   return new Auth($c['jwt']);
};

$container['query'] = function($c) {
   return new Query;
};