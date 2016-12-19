<?php

namespace App;

use Redis;
use Closure;

class Cache
{
   protected $redis;

   public function __construct(Redis $redis)
   {
      $this->redis = $redis;
   }

   public function get($key, $default = null)
   {
      $value = $this->redis->get($key);
      return $value ? $value : $default;
   }

   public function set($key, $value, $time = 1)
   {
      $this->redis->set($key, $value, $time*60);
   }

   public function remember($key, $time, Closure $value)
   {
      if (!$this->redis->get($key)) {
         $this->redis->set($key, serialize($value()), $time*60);
      }

      return unserialize($this->redis->get($key));
   }
}